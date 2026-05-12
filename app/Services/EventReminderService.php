<?php

namespace App\Services;

use App\Jobs\SendEventReminderEmailJob;
use App\Models\EstadoEvento;
use App\Models\Inscripcion;
use Carbon\CarbonImmutable;

class EventReminderService
{
    public function dispatch(?int $eventoId = null, bool $force = false): int
    {
        $now = CarbonImmutable::now(config('app.timezone'));
        $windowEnd = $now->addDays(2);
        $queued = 0;

        $query = Inscripcion::query()
            ->with(['user', 'evento.estado'])
            ->where('estado_check_in', '!=', Inscripcion::STATUS_CONFIRMED)
            ->whereHas('evento', function ($builder) use ($eventoId, $windowEnd): void {
                $builder->whereDate('fecha', '<=', $windowEnd->toDateString());

                if ($eventoId !== null) {
                    $builder->where('id', $eventoId);
                }
            });

        if (! $force) {
            $query->whereNull('recordatorio_enviado_at');
        }

        $query->lazyById()->each(function (Inscripcion $inscripcion) use (&$queued, $now, $force): void {
            if (! $inscripcion->evento || ! $inscripcion->evento->estado) {
                return;
            }

            if ($inscripcion->evento->estado->nombre === EstadoEvento::CANCELADO) {
                return;
            }

            $startsAt = $inscripcion->evento->startsAt();
            $reminderAt = $startsAt->subHours((int) config('eventos.notifications.reminder_hours', 24));

            if (! $force && ($now->lt($reminderAt) || $now->gte($startsAt))) {
                return;
            }

            if ($force && $now->gte($startsAt)) {
                return;
            }

            SendEventReminderEmailJob::dispatch($inscripcion->id)
                ->onQueue(config('eventos.notifications.queue'));

            $queued++;
        });

        return $queued;
    }
}
