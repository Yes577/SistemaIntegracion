<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\TipoRol;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalAdmins' => User::where('id_tipo_rol', TipoRol::ADMIN_ID)->count(),
            'totalStandardUsers' => User::where('id_tipo_rol', TipoRol::USER_ID)->count(),
            'totalEventos' => Evento::where('id_usuario', $user->id)->count(),
            'eventosPublicados' => Evento::where('id_usuario', $user->id)
                ->whereHas('estado', function ($query) {
                    $query->where('nombre', 'publicado');
                })->count(),
        ]);
    }
}
