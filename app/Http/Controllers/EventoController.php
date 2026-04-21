<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EstadoEvento;
use App\Models\Parqueadero;
use App\Models\TipoRol;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventoController extends Controller
{
    /**
     * Display a listing of the eventos.
     */
    public function index(): View
    {
        $query = Evento::with(['estado', 'usuario', 'parqueadero', 'inscripciones'])
            ->orderBy('fecha', 'desc');

        $user = auth()->user();

        if ($user) {
            if ($user->id_tipo_rol === TipoRol::ADMIN_ID) {
                // Administradores: solo ven sus propios eventos (independientemente del estado)
                $query->where('id_usuario', $user->id);
            } else {
                // Usuarios normales: ven todos los eventos de todos los administradores
                // pero filtrados por estado (publicado, cerrado o cancelado)
                $query->whereHas('estado', function ($q) {
                    $q->whereIn('nombre', [
                        EstadoEvento::PUBLICADO,
                        EstadoEvento::CERRADO,
                        EstadoEvento::CANCELADO,
                    ]);
                });
            }
        }

        $eventos = $query->paginate(10);

        $viewPath = request()->routeIs('admin.*') ? 'admin.eventos.index' : 'eventos.index';
        return view($viewPath, compact('eventos'));
    }

    /**
     * Show the form for creating a new evento.
     */
    public function create(): View
    {
        $estados = EstadoEvento::all();
        $viewPath = request()->routeIs('admin.*') ? 'admin.eventos.create' : 'eventos.create';
        return view($viewPath, compact('estados'));
    }

    /**
     * Store a newly created evento in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'lugar' => 'required|string|max:255',
            'capacidad_maxima' => 'required|integer|min:1',
            'id_estado' => 'required|exists:estados_evento,id',
            'tiene_parqueadero' => 'boolean',
            'parqueadero_capacidad' => 'nullable|required_if:tiene_parqueadero,true|integer|min:1',
            'parqueadero_cupos' => 'nullable|required_if:tiene_parqueadero,true|integer|min:1',
            'parqueadero_descripcion' => 'nullable|string',
        ]);

        $evento = Evento::create([
            'nombre'           => $validated['nombre'],
            'descripcion'      => $validated['descripcion'],
            'fecha'            => $validated['fecha'],
            'hora'             => $validated['hora'],
            'lugar'            => $validated['lugar'],
            'capacidad_maxima' => $validated['capacidad_maxima'],
            'capacidad_actual' => $validated['capacidad_maxima'], // Igual a maxima al crear
            'id_estado'        => $validated['id_estado'],
            'id_usuario'       => auth()->id(),
            'tiene_parqueadero' => $validated['tiene_parqueadero'] ?? false,
        ]);

        if ($evento->tiene_parqueadero) {
            Parqueadero::create([
                'id_usuario' => auth()->id(),
                'id_evento' => $evento->id,
                'capacidad_total' => $validated['parqueadero_capacidad'],
                'cupos_disponibles' => $validated['parqueadero_cupos'],
                'descripcion' => $validated['parqueadero_descripcion'],
            ]);
        }

        return redirect()->route('admin.eventos.index')->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified evento.
     */
    public function show(Evento $evento): View
    {
        $user = auth()->user();
        
        // Si es admin, verificar que sea el dueño
        if ($user && $user->id_tipo_rol === TipoRol::ADMIN_ID && $evento->id_usuario !== $user->id) {
            abort(403, 'No tienes permiso para ver este evento.');
        }

        $evento->load(['estado', 'usuario', 'parqueadero', 'inscripciones']);
        return view('eventos.show', compact('evento'));
    }

    /**
     * Show the form for editing the specified evento.
     */
    public function edit(Evento $evento): View
    {
        $user = auth()->user();
        
        // Solo el dueño administrador puede editar
        if ($evento->id_usuario !== $user->id) {
            abort(403, 'No puedes editar un evento que no creaste.');
        }

        $estados = EstadoEvento::all();
        $viewPath = request()->routeIs('admin.*') ? 'admin.eventos.edit' : 'eventos.edit';
        return view($viewPath, compact('evento', 'estados'));
    }

    /**
     * Update the specified evento in storage.
     */
    public function update(Request $request, Evento $evento)
    {
        $user = auth()->user();
        
        // Solo el dueño administrador puede actualizar
        if ($evento->id_usuario !== $user->id) {
            abort(403, 'No puedes actualizar un evento que no creaste.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'lugar' => 'required|string|max:255',
            'capacidad_maxima' => 'required|integer|min:1',
            'id_estado' => 'required|exists:estados_evento,id',
            'tiene_parqueadero' => 'boolean',
            'parqueadero_capacidad' => 'nullable|required_if:tiene_parqueadero,true|integer|min:1',
            'parqueadero_cupos' => 'nullable|required_if:tiene_parqueadero,true|integer|min:1',
            'parqueadero_descripcion' => 'nullable|string',
        ]);

        $evento->update([
            'nombre'            => $validated['nombre'],
            'descripcion'       => $validated['descripcion'],
            'fecha'             => $validated['fecha'],
            'hora'              => $validated['hora'],
            'lugar'             => $validated['lugar'],
            'capacidad_maxima'  => $validated['capacidad_maxima'],
            'capacidad_actual'  => $validated['capacidad_maxima'], // Se resetea al editar
            'id_estado'         => $validated['id_estado'],
            'tiene_parqueadero' => $validated['tiene_parqueadero'] ?? false,
        ]);

        // Handle parking if applicable
        if ($evento->tiene_parqueadero) {
            if ($evento->parqueadero) {
                $evento->parqueadero->update([
                    'capacidad_total' => $validated['parqueadero_capacidad'],
                    'cupos_disponibles' => $validated['parqueadero_cupos'],
                    'descripcion' => $validated['parqueadero_descripcion'],
                ]);
            } else {
                Parqueadero::create([
                    'id_usuario' => auth()->id(),
                    'id_evento' => $evento->id,
                    'capacidad_total' => $validated['parqueadero_capacidad'],
                    'cupos_disponibles' => $validated['parqueadero_cupos'],
                    'descripcion' => $validated['parqueadero_descripcion'],
                ]);
            }
        } else if ($evento->parqueadero) {
            $evento->parqueadero->delete();
        }

        return redirect()->route('admin.eventos.index')->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified evento from storage.
     */
    public function destroy(Evento $evento)
    {
        $user = auth()->user();
        
        // Solo el dueño administrador puede eliminar
        if ($evento->id_usuario !== $user->id) {
            abort(403, 'No puedes eliminar un evento que no creaste.');
        }

        $evento->delete();
        return redirect()->route('admin.eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
