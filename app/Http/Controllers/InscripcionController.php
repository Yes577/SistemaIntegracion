<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    /**
     * Inscribir al usuario autenticado en un evento (y opcionalmente en el parqueadero).
     */
    public function store(Request $request, Evento $evento)
    {
        $user = auth()->user();

        // Verificar que el evento tenga cupos disponibles
        if ($evento->capacidad_actual <= 0) {
            return back()->with('error', 'No hay cupos disponibles para este evento.');
        }

        // Verificar que el usuario no esté ya inscrito en este evento
        $yaInscrito = Inscripcion::where('id_user', $user->id)
            ->where('id_evento', $evento->id)
            ->exists();

        if ($yaInscrito) {
            return back()->with('error', 'Ya estás inscrito en este evento.');
        }

        $quiereParqueadero = $request->boolean('con_parqueadero');
        $parqueadero = $evento->parqueadero;

        // Validar parqueadero si lo solicitó
        if ($quiereParqueadero) {
            if (!$parqueadero || $parqueadero->cupos_disponibles <= 0) {
                return back()->with('error', 'No hay cupos de parqueadero disponibles.');
            }

            // Verificar que no tenga ya un parqueadero en este evento
            $yaConParqueadero = Inscripcion::where('id_user', $user->id)
                ->where('id_evento', $evento->id)
                ->whereNotNull('id_parqueadero')
                ->exists();

            if ($yaConParqueadero) {
                return back()->with('error', 'Ya tienes un parqueadero reservado para este evento.');
            }
        }

        DB::transaction(function () use ($user, $evento, $parqueadero, $quiereParqueadero) {
            // Crear inscripción
            Inscripcion::create([
                'id_user'       => $user->id,
                'id_evento'     => $evento->id,
                'id_parqueadero' => ($quiereParqueadero && $parqueadero) ? $parqueadero->id : null,
            ]);

            // Decrementar cupo del evento
            $evento->decrement('capacidad_actual');

            // Decrementar cupo del parqueadero si aplica
            if ($quiereParqueadero && $parqueadero) {
                $parqueadero->decrement('cupos_disponibles');
            }
        });

        return back()->with('success', '¡Inscripción realizada exitosamente!');
    }

    /**
     * Cancelar la inscripción del usuario autenticado en un evento.
     */
    public function destroy(Evento $evento)
    {
        $user = auth()->user();

        $inscripcion = Inscripcion::where('id_user', $user->id)
            ->where('id_evento', $evento->id)
            ->first();

        if (!$inscripcion) {
            return back()->with('error', 'No tienes una inscripción activa en este evento.');
        }

        DB::transaction(function () use ($inscripcion, $evento) {
            // Devolver cupo al evento
            $evento->increment('capacidad_actual');

            // Devolver cupo al parqueadero si tenía
            if ($inscripcion->id_parqueadero && $inscripcion->parqueadero) {
                $inscripcion->parqueadero->increment('cupos_disponibles');
            }

            $inscripcion->delete();
        });

        return back()->with('success', 'Tu inscripción ha sido cancelada.');
    }
}
