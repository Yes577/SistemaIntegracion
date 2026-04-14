<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EstadoEvento;
use App\Models\Parqueadero;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventoController extends Controller
{
    /**
     * Display a listing of the eventos.
     */
    public function index(): View
    {
        $eventos = Evento::with(['estado', 'usuario', 'parqueadero'])
            ->orderBy('fecha', 'desc')
            ->paginate(10);

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
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'lugar' => $validated['lugar'],
            'capacidad_maxima' => $validated['capacidad_maxima'],
            'id_estado' => $validated['id_estado'],
            'id_usuario' => auth()->id(),
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
        return view('eventos.show', compact('evento'));
    }

    /**
     * Show the form for editing the specified evento.
     */
    public function edit(Evento $evento): View
    {
        $estados = EstadoEvento::all();
        $viewPath = request()->routeIs('admin.*') ? 'admin.eventos.edit' : 'eventos.edit';
        return view($viewPath, compact('evento', 'estados'));
    }

    /**
     * Update the specified evento in storage.
     */
    public function update(Request $request, Evento $evento)
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

        $evento->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'lugar' => $validated['lugar'],
            'capacidad_maxima' => $validated['capacidad_maxima'],
            'id_estado' => $validated['id_estado'],
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
        $evento->delete();
        return redirect()->route('admin.eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
