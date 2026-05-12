<?php

namespace App\Services;

use App\Models\Evento;
use App\Models\Inscripcion;

class EventReportService
{
    /**
     * @return array{
     *     evento: Evento,
     *     inscritos_totales: int,
     *     asistentes_confirmados: int,
     *     porcentaje_asistencia: float,
     *     pendientes_check_in: int
     * }
     */
    public function build(Evento $evento): array
    {
        $evento->loadMissing(['estado', 'usuario', 'parqueadero', 'inscripciones.user', 'inscripciones.parqueadero']);

        $inscritosTotales = $evento->inscripciones->count();
        $asistentesConfirmados = $evento->inscripciones
            ->where('estado_check_in', Inscripcion::STATUS_CONFIRMED)
            ->count();

        return [
            'evento' => $evento,
            'inscritos_totales' => $inscritosTotales,
            'asistentes_confirmados' => $asistentesConfirmados,
            'porcentaje_asistencia' => $inscritosTotales > 0
                ? round(($asistentesConfirmados / $inscritosTotales) * 100, 1)
                : 0.0,
            'pendientes_check_in' => $inscritosTotales - $asistentesConfirmados,
        ];
    }
}
