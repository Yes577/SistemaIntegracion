<?php

namespace App\Events;

use App\Models\Inscripcion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InscripcionRegistrada
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Inscripcion $inscripcion)
    {
    }
}
