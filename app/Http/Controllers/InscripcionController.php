<?php

namespace App\Http\Controllers;

use App\Events\InscripcionRegistrada;
use App\Models\Evento;
use App\Models\Inscripcion;
use App\Services\QrTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    /**
     * Inscribir al usuario autenticado en un evento.
     */
    public function store(Request $request, Evento $evento, QrTokenService $qrTokenService)
    {
        $user = auth()->user();
        $inscripcion = null;

        if ($evento->capacidad_actual <= 0) {
            return back()->with('error', 'No hay cupos disponibles para este evento.');
        }

        $yaInscrito = Inscripcion::where('id_user', $user->id)
            ->where('id_evento', $evento->id)
            ->exists();

        if ($yaInscrito) {
            return back()->with('error', 'Ya estas inscrito en este evento.');
        }

        $quiereParqueadero = $request->boolean('con_parqueadero');
        $parqueadero = $evento->parqueadero;

        if ($quiereParqueadero) {
            if (! $parqueadero || $parqueadero->cupos_disponibles <= 0) {
                return back()->with('error', 'No hay cupos de parqueadero disponibles.');
            }

            $yaConParqueadero = Inscripcion::where('id_user', $user->id)
                ->where('id_evento', $evento->id)
                ->whereNotNull('id_parqueadero')
                ->exists();

            if ($yaConParqueadero) {
                return back()->with('error', 'Ya tienes un parqueadero reservado para este evento.');
            }
        }

        DB::transaction(function () use ($user, $evento, $parqueadero, $quiereParqueadero, $qrTokenService, &$inscripcion): void {
            $inscripcion = Inscripcion::create([
                'id_user' => $user->id,
                'id_evento' => $evento->id,
                'id_parqueadero' => ($quiereParqueadero && $parqueadero) ? $parqueadero->id : null,
                'estado_check_in' => Inscripcion::STATUS_PENDING,
                'qr_emitido_at' => now(),
                'qr_expira_at' => $qrTokenService->expiresAtForEvent($evento),
            ]);

            $evento->decrement('capacidad_actual');

            if ($quiereParqueadero && $parqueadero) {
                $parqueadero->decrement('cupos_disponibles');
            }
        });

        if ($inscripcion) {
            $inscripcion = $qrTokenService->syncInscripcion($inscripcion->loadMissing(['user', 'evento']));
            event(new InscripcionRegistrada($inscripcion));
        }

        return back()->with('success', 'Inscripcion realizada exitosamente. Recibiras el QR por correo.');
    }

    /**
     * Cancelar la inscripcion del usuario autenticado en un evento.
     */
    public function destroy(Evento $evento)
    {
        $user = auth()->user();

        $inscripcion = Inscripcion::where('id_user', $user->id)
            ->where('id_evento', $evento->id)
            ->first();

        if (! $inscripcion) {
            return back()->with('error', 'No tienes una inscripcion activa en este evento.');
        }

        DB::transaction(function () use ($inscripcion, $evento): void {
            $evento->increment('capacidad_actual');

            if ($inscripcion->id_parqueadero && $inscripcion->parqueadero) {
                $inscripcion->parqueadero->increment('cupos_disponibles');
            }

            $inscripcion->delete();
        });

        return back()->with('success', 'Tu inscripcion ha sido cancelada.');
    }
}
