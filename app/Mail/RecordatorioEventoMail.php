<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class RecordatorioEventoMail extends Mailable
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
            subject: 'Recordatorio de evento: '.$this->inscripcion->evento->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.eventos.recordatorio',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->qrPng, 'qr-recordatorio-'.$this->inscripcion->id.'.png')
                ->withMime('image/png'),
        ];
    }
}
