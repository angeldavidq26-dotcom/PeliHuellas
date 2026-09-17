<?php

namespace App\Notifications;

use App\Models\Mensaje;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MensajeRecibido extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Mensaje $mensaje)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Tenés un nuevo mensaje sobre :nombre', ['nombre' => $this->mensaje->solicitud->mascota->nombre]))
            ->line($this->mensaje->cuerpo)
            ->action(__('Responder'), $this->urlPara($notifiable));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('Nuevo mensaje sobre :nombre', ['nombre' => $this->mensaje->solicitud->mascota->nombre]),
            'body' => $this->mensaje->cuerpo,
            'url' => $this->urlPara($notifiable),
        ];
    }

    protected function urlPara(object $notifiable): string
    {
        $esFundacion = $this->mensaje->solicitud->mascota->fundacion?->usuario?->id_user === $notifiable->id;

        return $esFundacion ? route('fundacion.solicitudes') : route('solicitudes');
    }
}
