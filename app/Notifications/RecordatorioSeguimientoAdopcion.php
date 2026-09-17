<?php

namespace App\Notifications;

use App\Models\Adopcion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordatorioSeguimientoAdopcion extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Adopcion $adopcion)
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
        $mascota = $this->adopcion->solicitud->mascota;

        return (new MailMessage)
            ->subject(__('Es hora de hacer seguimiento a una adopción'))
            ->line(__('Ya pasó tiempo desde la adopción de :nombre. Registrá cómo está.', ['nombre' => $mascota->nombre]))
            ->action(__('Registrar seguimiento'), route('fundacion.adopciones.show', $this->adopcion));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $mascota = $this->adopcion->solicitud->mascota;

        return [
            'title' => __('Seguimiento de adopción pendiente'),
            'body' => __('Ya pasó tiempo desde la adopción de :nombre. Registrá cómo está.', ['nombre' => $mascota->nombre]),
            'url' => route('fundacion.adopciones.show', $this->adopcion),
        ];
    }
}
