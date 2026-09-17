<?php

namespace App\Notifications;

use App\Models\SolicitudAdopcion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SolicitudAdopcionCreada extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public SolicitudAdopcion $solicitud)
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
        $mascota = $this->solicitud->mascota;

        return (new MailMessage)
            ->subject(__('Nueva solicitud de adopción para :nombre', ['nombre' => $mascota->nombre]))
            ->line(__(':solicitante quiere adoptar a :nombre.', [
                'solicitante' => trim($this->solicitud->usuario->nombres.' '.$this->solicitud->usuario->primer_apellido),
                'nombre' => $mascota->nombre,
            ]))
            ->action(__('Ver solicitud'), route('fundacion.solicitudes'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $mascota = $this->solicitud->mascota;

        return [
            'title' => __('Nueva solicitud de adopción'),
            'body' => __(':solicitante quiere adoptar a :nombre.', [
                'solicitante' => trim($this->solicitud->usuario->nombres.' '.$this->solicitud->usuario->primer_apellido),
                'nombre' => $mascota->nombre,
            ]),
            'url' => route('fundacion.solicitudes'),
        ];
    }
}
