<?php

namespace App\Listeners;

use App\Events\InscripcionRegistrada;
use App\Jobs\SendInscripcionConfirmationEmailJob;

class QueueInscripcionConfirmationEmail
{
    public function handle(InscripcionRegistrada $event): void
    {
        SendInscripcionConfirmationEmailJob::dispatch($event->inscripcion->id)
            ->onQueue(config('eventos.notifications.queue'));
    }
}
