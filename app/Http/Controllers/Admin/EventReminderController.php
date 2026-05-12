<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Services\EventReminderService;
use Illuminate\Http\RedirectResponse;

class EventReminderController extends Controller
{
    public function store(Evento $evento, EventReminderService $eventReminderService): RedirectResponse
    {
        abort_unless($evento->id_usuario === auth()->id(), 403, 'No tienes permiso para enviar recordatorios.');

        $queued = $eventReminderService->dispatch($evento->id, false);

        if ($queued === 0) {
            return back()->with('error', 'No habia recordatorios pendientes para este evento.');
        }

        return back()->with('success', "Se encolaron {$queued} recordatorios para este evento.");
    }
}
