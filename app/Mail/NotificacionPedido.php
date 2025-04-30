<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
class NotificacionPedido extends Mailable
{
    use Queueable, SerializesModels;
    public $asunto;
    public $mensaje;
    public $destinatario;
    public $emisor;
    public $data;
    public function __construct($asunto,$mensaje, $emisor, $data)
    {
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->emisor = $emisor;
        $this->data = $data;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->emisor, 'Notificaciones Maploca - App'),
            subject: $this->asunto,
        );
    }
    public function build()
    {
        return $this->markdown('emails.notificacion_pedido');
    }
}