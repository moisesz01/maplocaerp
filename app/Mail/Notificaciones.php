<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class Notificaciones extends Mailable
{
    use Queueable, SerializesModels;
    public $asunto;
    public $mensaje;
    public $destinatario;
    public $emisor;
    /**
     * Create a new message instance.
     */
    public function __construct($asunto,$mensaje,$destinatario, $emisor)
    {
        try {
            $this->asunto = $asunto;
            $this->mensaje = $mensaje;
            $this->destinatario = $destinatario;
            $this->emisor = $emisor;
        } catch (Exception $e) {
            echo "Error instantiating Notificaciones class: " . $e->getMessage();
            die;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->emisor, 'Notificaciones Maploca App'),
            subject: $this->asunto,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notificaciones',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
