<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InscripcionConfirmadaMail extends Mailable
{
    use Queueable;

    public function __construct(
        public Inscripcion $inscripcion,
        public string $qrToken,
        public string $qrPng,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmacion de inscripcion: '.$this->inscripcion->evento->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inscripciones.confirmacion',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->qrPng, 'qr-inscripcion-'.$this->inscripcion->id.'.png')
                ->withMime('image/png'),
        ];
    }
}
