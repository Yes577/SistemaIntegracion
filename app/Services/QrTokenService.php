<?php

namespace App\Services;

use App\Models\Evento;
use App\Models\Inscripcion;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QrTokenService
{
    public function generateToken(Inscripcion $inscripcion): string
    {
        $inscripcion = $this->syncInscripcion($inscripcion);

        $payload = [
            'u' => $inscripcion->qr_uuid,
            'e' => $inscripcion->id_evento,
            'x' => $inscripcion->qr_expira_at?->timestamp,
        ];

        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));

        return $encodedPayload.'.'.$this->sign($encodedPayload);
    }

    /**
     * @return array{u: string, e: int, x: int}|null
     */
    public function parseToken(string $token): ?array
    {
        $token = trim($token);

        if ($token === '' || ! str_contains($token, '.')) {
            return null;
        }

        [$encodedPayload, $signature] = explode('.', $token, 2);

        if (! hash_equals($this->sign($encodedPayload), $signature)) {
            return null;
        }

        $decodedPayload = $this->base64UrlDecode($encodedPayload);

        if ($decodedPayload === false) {
            return null;
        }

        /** @var mixed $payload */
        $payload = json_decode($decodedPayload, true);

        if (! is_array($payload)) {
            return null;
        }

        $uuid = Arr::get($payload, 'u');
        $eventoId = Arr::get($payload, 'e');
        $expiresAt = Arr::get($payload, 'x');

        if (! is_string($uuid) || ! Str::isUuid($uuid) || ! is_numeric($eventoId) || ! is_numeric($expiresAt)) {
            return null;
        }

        return [
            'u' => $uuid,
            'e' => (int) $eventoId,
            'x' => (int) $expiresAt,
        ];
    }

    public function syncInscripcion(Inscripcion $inscripcion, bool $forceRefresh = false): Inscripcion
    {
        $inscripcion->loadMissing('evento');

        $updates = [];

        if (! $inscripcion->qr_uuid) {
            $updates['qr_uuid'] = (string) Str::uuid();
        }

        if ($forceRefresh || ! $inscripcion->qr_emitido_at) {
            $updates['qr_emitido_at'] = now();
        }

        if ($forceRefresh || ! $inscripcion->qr_expira_at) {
            $updates['qr_expira_at'] = $this->expiresAtForEvent($inscripcion->evento);
        }

        if ($forceRefresh && $inscripcion->estado_check_in !== Inscripcion::STATUS_CONFIRMED) {
            $updates['estado_check_in'] = Inscripcion::STATUS_PENDING;
        }

        if ($updates !== []) {
            $inscripcion->forceFill($updates)->save();
            $inscripcion->refresh();
        }

        return $inscripcion;
    }

    public function expiresAtForEvent(Evento $evento): CarbonImmutable
    {
        return $evento->startsAt()->addHours((int) config('eventos.qr.ttl_hours', 24));
    }

    public function syncInscripcionesForEvent(Evento $evento, bool $resetReminder = false): void
    {
        $expiresAt = $this->expiresAtForEvent($evento);

        DB::transaction(function () use ($evento, $expiresAt, $resetReminder): void {
            $evento->inscripciones()
                ->select('inscripciones.*')
                ->lazyById()
                ->each(function (Inscripcion $inscripcion) use ($expiresAt, $resetReminder): void {
                    $updates = [
                        'qr_expira_at' => $expiresAt,
                    ];

                    if ($resetReminder) {
                        $updates['recordatorio_enviado_at'] = null;
                    }

                    if ($inscripcion->estado_check_in !== Inscripcion::STATUS_CONFIRMED) {
                        $updates['estado_check_in'] = Inscripcion::STATUS_PENDING;
                    }

                    $inscripcion->forceFill($updates)->save();
                });
        });
    }

    private function sign(string $encodedPayload): string
    {
        return hash_hmac('sha256', $encodedPayload, $this->signingKey());
    }

    private function signingKey(): string
    {
        $appKey = (string) config('app.key', '');

        if (str_starts_with($appKey, 'base64:')) {
            $decoded = base64_decode(Str::after($appKey, 'base64:'), true);

            return $decoded === false ? $appKey : $decoded;
        }

        return $appKey;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string|false
    {
        $padding = strlen($value) % 4;

        if ($padding !== 0) {
            $value .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr($value, '-_', '+/'), true);
    }
}
