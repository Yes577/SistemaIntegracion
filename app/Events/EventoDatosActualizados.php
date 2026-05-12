<?php

namespace App\Events;

use App\Models\Evento;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventoDatosActualizados
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param  array<string, array{anterior: string, nuevo: string}>  $cambios
     */
    public function __construct(
        public Evento $evento,
        public array $cambios,
    ) {
    }
}
