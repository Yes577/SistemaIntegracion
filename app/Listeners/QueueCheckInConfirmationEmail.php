<?php

namespace App\Listeners;

use App\Events\CheckInRegistrado;
use App\Jobs\SendCheckInConfirmationEmailJob;

class QueueCheckInConfirmationEmail
{
    public function handle(CheckInRegistrado $event): void
    {
        SendCheckInConfirmationEmailJob::dispatch($event->inscripcion->id)
            ->onQueue(config('eventos.notifications.queue'));
    }
}
