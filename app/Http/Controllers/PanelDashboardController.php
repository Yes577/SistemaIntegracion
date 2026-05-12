<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\View\View;

class PanelDashboardController extends Controller
{
    public function index(): View
    {
        $inscripciones = Inscripcion::query()
            ->with(['evento.estado', 'evento.parqueadero'])
            ->where('id_user', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('panel.dashboard', compact('inscripciones'));
    }
}
