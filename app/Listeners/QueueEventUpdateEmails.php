<?php

namespace App\Listeners;

use App\Events\EventoDatosActualizados;
use App\Jobs\SendEventUpdatedEmailJob;
use App\Models\Inscripcion;

class QueueEventUpdateEmails
{
    public function handle(EventoDatosActualizados $event): void
    {
        Inscripcion::query()
            ->where('id_evento', $event->evento->id)
            ->select('id')
            ->lazyById()
            ->each(function (Inscripcion $inscripcion) use ($event): void {
                SendEventUpdatedEmailJob::dispatch($inscripcion->id, $event->cambios)
                    ->onQueue(config('eventos.notifications.queue'));
            });
    }
}
