<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Inscripcion;
use App\Services\CheckInService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventCheckInController extends Controller
{
    public function show(Evento $evento): View
    {
        abort_unless($evento->id_usuario === auth()->id(), 403, 'No tienes permiso para operar este evento.');

        $recentCheckIns = $evento->inscripciones()
            ->with('user')
            ->where('estado_check_in', Inscripcion::STATUS_CONFIRMED)
            ->orderByDesc('check_in_at')
            ->limit(10)
            ->get();

        return view('admin.eventos.checkin', compact('evento', 'recentCheckIns'));
    }

    public function store(Request $request, Evento $evento, CheckInService $checkInService): JsonResponse
    {
        abort_unless($evento->id_usuario === auth()->id(), 403, 'No tienes permiso para operar este evento.');

        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $result = $checkInService->register($evento, $validated['token']);

        $payload = [
            'ok' => $result->successful,
            'message' => $result->message,
            'reason' => $result->reason,
        ];

        if ($result->inscripcion) {
            $payload['inscripcion'] = [
                'id' => $result->inscripcion->id,
                'usuario' => $result->inscripcion->user?->name,
                'email' => $result->inscripcion->user?->email,
                'check_in_at' => $result->inscripcion->check_in_at?->format('d/m/Y H:i'),
            ];
        }

        return response()->json($payload, $result->statusCode);
    }
}
