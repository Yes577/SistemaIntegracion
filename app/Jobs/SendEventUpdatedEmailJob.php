<?php

namespace App\Jobs;

use App\Mail\CambioEventoMail;
use App\Models\Inscripcion;
use App\Services\QrCodeService;
use App\Services\QrTokenService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEventUpdatedEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    /**
     * @param  array<string, array{anterior: string, nuevo: string}>  $cambios
     */
    public function __construct(
        public int $inscripcionId,
        public array $cambios,
    ) {
        $this->afterCommit();
    }

    public function handle(QrTokenService $qrTokenService, QrCodeService $qrCodeService): void
    {
        $inscripcion = Inscripcion::query()
            ->with(['user', 'evento'])
            ->find($this->inscripcionId);

        if (! $inscripcion || ! $inscripcion->user) {
            return;
        }

        $inscripcion = $qrTokenService->syncInscripcion($inscripcion, forceRefresh: true);
        $token = $qrTokenService->generateToken($inscripcion);
        $qrPng = $qrCodeService->generatePng($token);

        Mail::to($inscripcion->user->email)->send(
            new CambioEventoMail($inscripcion, $this->cambios, $token, $qrPng)
        );
    }
}
