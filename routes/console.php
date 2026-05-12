<?php

use App\Services\EventReminderService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('eventos:enviar-recordatorios {--evento_id=} {--forzar}', function (EventReminderService $eventReminderService) {
    $queued = $eventReminderService->dispatch(
        $this->option('evento_id') ? (int) $this->option('evento_id') : null,
        (bool) $this->option('forzar'),
    );

    $this->info("Recordatorios encolados: {$queued}");
})->purpose('Encola recordatorios automaticos de eventos');

Schedule::command('eventos:enviar-recordatorios')->everyFiveMinutes()->withoutOverlapping();
