<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\View\View;

class AdminInscripcionController extends Controller
{
    /**
     * Mostrar la lista de inscritos por cada evento del administrador.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Obtenemos los eventos del administrador con sus inscripciones, usuarios y parqueaderos
        $eventos = Evento::where('id_usuario', $user->id)
            ->with(['inscripciones.user', 'inscripciones.parqueadero', 'estado'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('admin.inscritos.index', compact('eventos'));
    }
}
