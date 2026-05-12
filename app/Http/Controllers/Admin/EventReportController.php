<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Services\EventReportService;
use Illuminate\View\View;

class EventReportController extends Controller
{
    public function show(Evento $evento, EventReportService $eventReportService): View
    {
        abort_unless($evento->id_usuario === auth()->id(), 403, 'No tienes permiso para ver este reporte.');

        return view('admin.eventos.report', $eventReportService->build($evento));
    }
}
