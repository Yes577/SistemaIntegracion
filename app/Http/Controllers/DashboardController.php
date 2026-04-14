<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalEventos = Evento::count();
        $eventosProximos = Evento::where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha', 'asc')
            ->limit(5)
            ->get();

        $user = auth()->user();

        return view('dashboard', compact('totalEventos', 'eventosProximos', 'user'));
    }
}
