<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CheckInConfirmadoMail extends Mailable
{
    use Queueable;

    public function __construct(
        public Inscripcion $inscripcion,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Asistencia confirmada: '.$this->inscripcion->evento->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inscripciones.checkin',
        );
    }
}
