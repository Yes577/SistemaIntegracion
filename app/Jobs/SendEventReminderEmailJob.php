<?php

namespace App\Jobs;

use App\Mail\RecordatorioEventoMail;
use App\Models\EstadoEvento;
use App\Models\Inscripcion;
use App\Services\QrCodeService;
use App\Services\QrTokenService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEventReminderEmailJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public int $uniqueFor = 3600;

    public function __construct(
        public int $inscripcionId,
    ) {
        $this->afterCommit();
    }

    public function uniqueId(): string
    {
        return (string) $this->inscripcionId;
    }

    public function handle(QrTokenService $qrTokenService, QrCodeService $qrCodeService): void
    {
        $inscripcion = Inscripcion::query()
            ->with(['user', 'evento.estado'])
            ->find($this->inscripcionId);

        if (! $inscripcion || ! $inscripcion->user || ! $inscripcion->evento || ! $inscripcion->evento->estado) {
            return;
        }

        if ($inscripcion->evento->estado->nombre === EstadoEvento::CANCELADO || now()->gte($inscripcion->evento->startsAt())) {
            return;
        }

        $token = $qrTokenService->generateToken($inscripcion);
        $qrPng = $qrCodeService->generatePng($token);

        Mail::to($inscripcion->user->email)->send(
            new RecordatorioEventoMail($inscripcion, $token, $qrPng)
        );

        $inscripcion->forceFill([
            'recordatorio_enviado_at' => now(),
        ])->save();
    }
}
