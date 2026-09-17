<?php

namespace App\Notifications;

use App\Models\SolicitudAdopcion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SolicitudAdopcionResuelta extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject(__('Actualización de tu solicitud de adopción'))
            ->line($this->mensaje())
            ->action(__('Ver mis solicitudes'), route('solicitudes'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('Tu solicitud de adopción cambió de estado'),
            'body' => $this->mensaje(),
            'url' => route('solicitudes'),
        ];
    }

    protected function mensaje(): string
    {
        $nombre = $this->solicitud->mascota->nombre;

        return match ($this->solicitud->estado) {
            'aprobada' => __('¡Buenas noticias! Tu solicitud para adoptar a :nombre fue aprobada.', ['nombre' => $nombre]),
            'rechazada' => __('Tu solicitud para adoptar a :nombre fue rechazada.', ['nombre' => $nombre]),
            'completada' => __('¡La adopción de :nombre se completó! Gracias por darle un hogar.', ['nombre' => $nombre]),
            'no_concretada' => __('La adopción de :nombre no se concretó.', ['nombre' => $nombre]),
            default => __('Tu solicitud para adoptar a :nombre cambió de estado.', ['nombre' => $nombre]),
        };
    }
}
