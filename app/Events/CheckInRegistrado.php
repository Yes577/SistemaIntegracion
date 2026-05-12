<?php

namespace App\Events;

use App\Models\Inscripcion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckInRegistrado
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Inscripcion $inscripcion)
    {
    }
}
