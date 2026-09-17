<?php

namespace App\Notifications;

use App\Models\Mascota;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MascotaPublicadaCoincide extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Mascota $mascota)
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
            ->subject(__('Un nuevo animal que podría interesarte'))
            ->line(__(':nombre está disponible para adopción y coincide con lo que buscás.', ['nombre' => $this->mascota->nombre]))
            ->action(__('Ver a :nombre', ['nombre' => $this->mascota->nombre]), route('mascotas.show', $this->mascota));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('Nuevo animal disponible'),
            'body' => __(':nombre está disponible para adopción y coincide con lo que buscás.', ['nombre' => $this->mascota->nombre]),
            'url' => route('mascotas.show', $this->mascota),
        ];
    }
}
