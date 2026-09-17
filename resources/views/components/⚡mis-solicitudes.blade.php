<?php

use App\Models\SolicitudAdopcion;
use App\Models\Usuario;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function usuario(): ?Usuario
    {
        return Usuario::paraUser(auth()->user());
    }

    #[Computed]
    public function solicitudes(): Collection
    {
        if (! $this->usuario) {
            return collect();
        }

        return SolicitudAdopcion::query()
            ->where('id_usuario', $this->usuario->id_usuario)
            ->with(['mascota.fundacion', 'mascota.sede', 'mascota.fotoPrincipal'])
            ->latest('fecha_solicitud')
            ->get();
    }

    public function cancelar(int $idSolicitud): void
    {
        $solicitud = SolicitudAdopcion::query()
            ->where('id_usuario', $this->usuario?->id_usuario)
            ->where('estado', 'pendiente')
            ->findOrFail($idSolicitud);

        $solicitud->update(['estado' => 'cancelada', 'fecha_resolucion' => now()]);

        unset($this->solicitudes);

        Flux::toast(variant: 'success', text: __('Cancelaste tu solicitud.'));
    }
}; ?>

<div>
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Mis solicitudes') }}</h1>
            <p class="text-neutral-500">{{ __('Seguimiento de tus solicitudes de adopción.') }}</p>
        </div>
        <flux:button :href="route('mascotas')" variant="primary" wire:navigate>{{ __('Ver animales') }}</flux:button>
    </div>

    @if ($this->solicitudes->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('Todavía no enviaste ninguna solicitud de adopción.') }}
        </div>
    @else
        <div class="space-y-4">
            @foreach ($this->solicitudes as $solicitud)
                <div wire:key="solicitud-{{ $solicitud->id_solicitud }}" class="rounded-xl border border-neutral-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <img
                                src="{{ $solicitud->mascota->fotoPrincipal?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=200&q=80' }}"
                                alt="{{ $solicitud->mascota->nombre }}"
                                class="size-14 shrink-0 rounded-lg object-cover"
                            >
                            <div>
                                <h3 class="font-semibold">{{ $solicitud->mascota->nombre }}</h3>
                                <p class="text-sm text-neutral-500">
                                    {{ $solicitud->mascota->fundacion?->nombre }} · {{ $solicitud->mascota->sede?->ciudad }}
                                </p>
                                <p class="text-xs text-neutral-400">
                                    {{ __('Enviada el :fecha', ['fecha' => $solicitud->fecha_solicitud->translatedFormat('d M Y')]) }}
                                </p>
                            </div>
                        </div>

                        <span @class([
                            'shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold',
                            'bg-[#dcece4] text-[#234a3a]' => in_array($solicitud->estado, ['aprobada', 'completada'], true),
                            'bg-amber-100 text-amber-700' => in_array($solicitud->estado, ['pendiente', 'en_pausa'], true),
                            'bg-neutral-100 text-neutral-600' => in_array($solicitud->estado, ['rechazada', 'no_concretada', 'cancelada'], true),
                        ])>
                            {{ match ($solicitud->estado) {
                                'pendiente' => __('Pendiente'),
                                'aprobada' => __('Aprobada'),
                                'en_pausa' => __('En pausa'),
                                'rechazada' => __('Rechazada'),
                                'completada' => __('Completada'),
                                'no_concretada' => __('No concretada'),
                                'cancelada' => __('Cancelada'),
                                default => ucfirst($solicitud->estado),
                            } }}
                        </span>
                    </div>

                    @if ($solicitud->estado === 'aprobada')
                        <div class="mt-4 rounded-lg bg-[#f2f8f5] px-4 py-3 text-sm text-[#234a3a]">
                            {{ __('¡Te escogieron! La fundación se va a poner en contacto para coordinar la entrega.') }}
                        </div>
                    @elseif ($solicitud->estado === 'en_pausa')
                        <div
                            x-data="{ open: false }"
                            class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900"
                        >
                            <button type="button" x-on:click="open = ! open" class="flex w-full items-center justify-between gap-2 text-left font-medium">
                                {{ __('¿Por qué está en pausa mi solicitud?') }}
                                <flux:icon name="chevron-down" variant="micro" class="size-4 shrink-0" x-bind:class="open && 'rotate-180'" />
                            </button>
                            <div x-show="open" x-cloak class="mt-2 space-y-2 text-amber-800">
                                <p>{{ $solicitud->observaciones_fundacion ?: __('La fundación está avanzando el proceso con otro adoptante primero. Tu solicitud no fue rechazada.') }}</p>
                                <p>{{ __('Si ese proceso no resulta, tu solicitud vuelve a quedar activa automáticamente y la fundación se pondrá en contacto. No necesitás hacer nada.') }}</p>
                            </div>
                        </div>
                    @elseif ($solicitud->estado === 'rechazada' && $solicitud->observaciones_fundacion)
                        <div class="mt-4 rounded-lg bg-neutral-50 px-4 py-3 text-sm text-neutral-600">
                            {{ $solicitud->observaciones_fundacion }}
                        </div>
                    @endif

                    @if ($solicitud->estado === 'pendiente')
                        <div class="mt-4 text-right">
                            <flux:button
                                size="sm"
                                variant="ghost"
                                wire:click="cancelar({{ $solicitud->id_solicitud }})"
                                wire:confirm="{{ __('¿Cancelar esta solicitud?') }}"
                            >
                                {{ __('Cancelar solicitud') }}
                            </flux:button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
