<?php

namespace App\Http\Controllers;

use App\Events\EventoDatosActualizados;
use App\Models\Evento;
use App\Models\EstadoEvento;
use App\Models\Parqueadero;
use App\Models\TipoRol;
use App\Services\QrTokenService;
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
                $query->where('id_usuario', $user->id);
            } else {
                $query->whereHas('estado', function ($q): void {
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
            'parqueadero_cupos' => 'nullable|required_if:tiene_parqueadero,true|integer|min:0',
            'parqueadero_descripcion' => 'nullable|string',
        ]);

        $evento = Evento::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'lugar' => $validated['lugar'],
            'capacidad_maxima' => $validated['capacidad_maxima'],
            'capacidad_actual' => $validated['capacidad_maxima'],
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
        $user = auth()->user();

        if ($user && $user->id_tipo_rol === TipoRol::ADMIN_ID && $evento->id_usuario !== $user->id) {
            abort(403, 'No tienes permiso para ver este evento.');
        }

        $evento->load(['estado', 'usuario', 'parqueadero', 'inscripciones.user']);

        return view('eventos.show', compact('evento'));
    }

    /**
     * Show the form for editing the specified evento.
     */
    public function edit(Evento $evento): View
    {
        $user = auth()->user();

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
    public function update(Request $request, Evento $evento, QrTokenService $qrTokenService)
    {
        $user = auth()->user();

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
            'parqueadero_cupos' => 'nullable|required_if:tiene_parqueadero,true|integer|min:0',
            'parqueadero_descripcion' => 'nullable|string',
        ]);

        $inscritosActuales = $evento->inscripciones()->count();
        $reservasParqueadero = $evento->inscripciones()->whereNotNull('id_parqueadero')->count();

        if ($validated['capacidad_maxima'] < $inscritosActuales) {
            return back()
                ->withErrors([
                    'capacidad_maxima' => "La capacidad maxima no puede ser menor al total de inscritos actuales ({$inscritosActuales}).",
                ])
                ->withInput();
        }

        if (($validated['tiene_parqueadero'] ?? false)
            && (($validated['parqueadero_cupos'] ?? 0) + $reservasParqueadero) > ($validated['parqueadero_capacidad'] ?? 0)) {
            return back()
                ->withErrors([
                    'parqueadero_cupos' => 'Los cupos disponibles del parqueadero no son consistentes con las reservas ya existentes.',
                ])
                ->withInput();
        }

        $cambios = [];
        $fechaAnterior = $evento->fecha->format('Y-m-d');
        $horaAnterior = $evento->hora->format('H:i');
        $lugarAnterior = $evento->lugar;

        if ($fechaAnterior !== $validated['fecha']) {
            $cambios['fecha'] = ['anterior' => $fechaAnterior, 'nuevo' => $validated['fecha']];
        }

        if ($horaAnterior !== $validated['hora']) {
            $cambios['hora'] = ['anterior' => $horaAnterior, 'nuevo' => $validated['hora']];
        }

        if ($lugarAnterior !== $validated['lugar']) {
            $cambios['lugar'] = ['anterior' => $lugarAnterior, 'nuevo' => $validated['lugar']];
        }

        $evento->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'lugar' => $validated['lugar'],
            'capacidad_maxima' => $validated['capacidad_maxima'],
            'capacidad_actual' => $validated['capacidad_maxima'] - $inscritosActuales,
            'id_estado' => $validated['id_estado'],
            'tiene_parqueadero' => $validated['tiene_parqueadero'] ?? false,
        ]);

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
        } elseif ($evento->parqueadero) {
            $evento->parqueadero->delete();
        }

        if (array_intersect(array_keys($cambios), ['fecha', 'hora']) !== []) {
            $qrTokenService->syncInscripcionesForEvent($evento->fresh(), true);
        }

        if ($cambios !== [] && $evento->inscripciones()->exists()) {
            event(new EventoDatosActualizados($evento->fresh(), $cambios));
        }

        return redirect()->route('admin.eventos.index')->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified evento from storage.
     */
    public function destroy(Evento $evento)
    {
        $user = auth()->user();

        if ($evento->id_usuario !== $user->id) {
            abort(403, 'No puedes eliminar un evento que no creaste.');
        }

        $evento->delete();

        return redirect()->route('admin.eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
