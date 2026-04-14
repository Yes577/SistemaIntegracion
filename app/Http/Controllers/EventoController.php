<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EstadoEvento;
use App\Models\Parqueadero;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the eventos.
     */
    public function index(): View
    {
        $eventos = Evento::with(['estado', 'usuario', 'parqueadero'])
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return view('eventos.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new evento.
     */
    public function create(): View
    {
        $this->authorize('create', Evento::class);

        $estados = EstadoEvento::all();
        return view('eventos.create', compact('estados'));
    }

    /**
     * Store a newly created evento in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Evento::class);

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

        return redirect()->route('eventos.show', $evento)->with('success', 'Evento creado exitosamente.');
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
        $this->authorize('update', $evento);

        $estados = EstadoEvento::all();
        return view('eventos.edit', compact('evento', 'estados'));
    }

    /**
     * Update the specified evento in storage.
     */
    public function update(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

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

        return redirect()->route('eventos.show', $evento)->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified evento from storage.
     */
    public function destroy(Evento $evento)
    {
        $this->authorize('delete', $evento);

        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
