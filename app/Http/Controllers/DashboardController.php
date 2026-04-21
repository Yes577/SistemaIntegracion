<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\TipoRol;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $query = Evento::query();

        if ($user->id_tipo_rol === TipoRol::ADMIN_ID) {
            // Administradores solo ven lo suyo
            $query->where('id_usuario', $user->id);
        } else {
            // Usuarios normales ven todos los eventos públicos
            $query->whereHas('estado', function ($q) {
                $q->whereIn('nombre', ['publicado', 'cerrado', 'cancelado']);
            });
        }

        $totalEventos = (clone $query)->count();
        $eventosProximos = (clone $query)->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('totalEventos', 'eventosProximos', 'user'));
    }
}
