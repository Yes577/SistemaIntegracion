<?php

namespace App\Services;

use App\DataTransferObjects\CheckInResult;
use App\Events\CheckInRegistrado;
use App\Models\Evento;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;

class CheckInService
{
    public function __construct(
        private readonly QrTokenService $qrTokenService,
    ) {
    }

    public function register(Evento $evento, string $token): CheckInResult
    {
        $payload = $this->qrTokenService->parseToken($token);

        if ($payload === null) {
            return CheckInResult::error('El codigo QR no es valido o fue alterado.', reason: 'invalid');
        }

        if ($payload['e'] !== $evento->id) {
            return CheckInResult::error('Este codigo QR no pertenece al evento seleccionado.', reason: 'wrong_event');
        }

        $result = DB::transaction(function () use ($evento, $payload): CheckInResult {
            $inscripcion = Inscripcion::query()
                ->with(['user', 'evento'])
                ->where('id_evento', $evento->id)
                ->where('qr_uuid', $payload['u'])
                ->lockForUpdate()
                ->first();

            if (! $inscripcion) {
                return CheckInResult::error('No se encontro una inscripcion asociada a este codigo QR.', reason: 'not_found');
            }

            $this->qrTokenService->syncInscripcion($inscripcion);
            $inscripcion->refresh();

            if ($inscripcion->estado_check_in === Inscripcion::STATUS_CONFIRMED || $inscripcion->check_in_at) {
                $fechaUso = $inscripcion->check_in_at?->timezone(config('app.timezone'))->format('d/m/Y H:i');

                return CheckInResult::error(
                    'Este codigo QR ya fue utilizado'.($fechaUso ? " el {$fechaUso}." : '.'),
                    reason: 'already_used',
                );
            }

            if ($inscripcion->isQrExpired()) {
                $inscripcion->forceFill([
                    'estado_check_in' => Inscripcion::STATUS_EXPIRED,
                ])->save();

                return CheckInResult::error('Este codigo QR ya expiro y no puede utilizarse.', reason: 'expired');
            }

            $inscripcion->forceFill([
                'estado_check_in' => Inscripcion::STATUS_CONFIRMED,
                'check_in_at' => now(),
            ])->save();

            return CheckInResult::success(
                sprintf('Check-in registrado correctamente para %s.', $inscripcion->user->name),
                $inscripcion->fresh(['user', 'evento']),
            );
        });

        if ($result->successful && $result->inscripcion) {
            event(new CheckInRegistrado($result->inscripcion));
        }

        return $result;
    }
}
