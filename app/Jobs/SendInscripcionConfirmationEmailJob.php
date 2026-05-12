<?php

namespace App\Jobs;

use App\Mail\InscripcionConfirmadaMail;
use App\Models\Inscripcion;
use App\Services\QrCodeService;
use App\Services\QrTokenService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInscripcionConfirmationEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public int $inscripcionId,
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

        $token = $qrTokenService->generateToken($inscripcion);
        $qrPng = $qrCodeService->generatePng($token);

        Mail::to($inscripcion->user->email)->send(
            new InscripcionConfirmadaMail($inscripcion, $token, $qrPng)
        );
    }
}
