<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CambioEventoMail extends Mailable
{
    use Queueable;

    /**
     * @param  array<string, array{anterior: string, nuevo: string}>  $cambios
     */
    public function __construct(
        public Inscripcion $inscripcion,
        public array $cambios,
        public string $qrToken,
        public string $qrPng,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Actualizacion del evento: '.$this->inscripcion->evento->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.eventos.cambio',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->qrPng, 'qr-actualizado-'.$this->inscripcion->id.'.png')
                ->withMime('image/png'),
        ];
    }
}
