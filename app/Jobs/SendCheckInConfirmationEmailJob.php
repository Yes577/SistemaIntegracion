<?php

namespace App\Jobs;

use App\Mail\CheckInConfirmadoMail;
use App\Models\Inscripcion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCheckInConfirmationEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public int $inscripcionId,
    ) {
        $this->afterCommit();
    }

    public function handle(): void
    {
        $inscripcion = Inscripcion::query()
            ->with(['user', 'evento'])
            ->find($this->inscripcionId);

        if (! $inscripcion || ! $inscripcion->user || ! $inscripcion->check_in_at) {
            return;
        }

        Mail::to($inscripcion->user->email)->send(
            new CheckInConfirmadoMail($inscripcion)
        );
    }
}
