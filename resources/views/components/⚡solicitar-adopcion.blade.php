<?php

use App\Models\Mascota;
use App\Models\SolicitudAdopcion;
use App\Models\Usuario;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Mascota $mascota;

    public function mount(Mascota $mascota): void
    {
        $this->mascota = $mascota;
    }

    #[Computed]
    public function usuario(): ?Usuario
    {
        return auth()->check() ? Usuario::paraUser(auth()->user()) : null;
    }

    #[Computed]
    public function perfilCompleto(): bool
    {
        return (bool) $this->usuario?->perfilAdoptante?->completo;
    }

    #[Computed]
    public function solicitudExistente(): ?SolicitudAdopcion
    {
        if (! $this->usuario) {
            return null;
        }

        return SolicitudAdopcion::query()
            ->where('id_usuario', $this->usuario->id_usuario)
            ->where('id_mascota', $this->mascota->id_mascota)
            ->whereNotIn('estado', ['rechazada', 'cancelada', 'no_concretada'])
            ->latest('fecha_solicitud')
            ->first();
    }

    public function solicitar(): void
    {
        if (! $this->perfilCompleto || $this->solicitudExistente) {
            return;
        }

        SolicitudAdopcion::create([
            'id_usuario' => $this->usuario->id_usuario,
            'id_mascota' => $this->mascota->id_mascota,
            'fecha_solicitud' => now(),
            'estado' => 'pendiente',
        ]);

        unset($this->solicitudExistente);

        Flux::toast(variant: 'success', text: __('Enviamos tu solicitud de adopción. Podés seguirla desde "Solicitudes".'));
    }
}; ?>

<div>
    @if ($mascota->estado !== 'disponible')
        <div class="mb-4 rounded-lg bg-neutral-100 px-3 py-2 text-center text-sm text-neutral-600">
            {{ $mascota->estado === 'adoptado' ? __('Este animal ya fue adoptado.') : __('Este animal ya tiene una adopción en curso.') }}
        </div>
    @elseif (! auth()->check())
        <flux:button :href="route('login')" variant="primary" class="w-full" wire:navigate>
            {{ __('Iniciá sesión para adoptar') }}
        </flux:button>
        <p class="mt-2 text-center text-xs text-neutral-500">
            {{ __('Necesitás una cuenta para solicitar una adopción.') }}
        </p>
    @elseif ($this->solicitudExistente)
        <div class="rounded-lg bg-[#dcece4] px-3 py-2 text-center text-sm text-[#234a3a]">
            {{ __('Ya enviaste una solicitud para :nombre.', ['nombre' => $mascota->nombre]) }}
        </div>
        <flux:button :href="route('solicitudes')" variant="filled" class="mt-2 w-full" wire:navigate>
            {{ __('Ver mis solicitudes') }}
        </flux:button>
    @elseif (! $this->perfilCompleto)
        <flux:button :href="route('perfil-adoptante', ['volver' => $mascota->id_mascota])" variant="primary" class="w-full" wire:navigate>
            {{ __('Completar mi perfil primero') }}
        </flux:button>
        <p class="mt-2 text-center text-xs text-neutral-500">
            {{ __('Completá tu perfil de adoptante antes de poder solicitar.') }}
        </p>
    @else
        <flux:button wire:click="solicitar" variant="primary" class="w-full">
            {{ __('Solicitar adopción') }}
        </flux:button>
    @endif
</div>
