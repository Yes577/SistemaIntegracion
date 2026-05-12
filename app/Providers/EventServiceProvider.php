<?php

namespace App\Providers;

use App\Events\CheckInRegistrado;
use App\Events\EventoDatosActualizados;
use App\Events\InscripcionRegistrada;
use App\Listeners\QueueCheckInConfirmationEmail;
use App\Listeners\QueueEventUpdateEmails;
use App\Listeners\QueueInscripcionConfirmationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        InscripcionRegistrada::class => [
            QueueInscripcionConfirmationEmail::class,
        ],
        EventoDatosActualizados::class => [
            QueueEventUpdateEmails::class,
        ],
        CheckInRegistrado::class => [
            QueueCheckInConfirmationEmail::class,
        ],
    ];
}
