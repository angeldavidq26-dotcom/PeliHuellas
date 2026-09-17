<?php

use App\Models\Mensaje;
use App\Models\SolicitudAdopcion;
use App\Models\Usuario;
use App\Notifications\MensajeRecibido;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public ?int $chatSolicitudId = null;

    public string $nuevoMensaje = '';

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

    public function abrirChat(int $idSolicitud): void
    {
        $solicitud = SolicitudAdopcion::query()
            ->where('id_usuario', $this->usuario?->id_usuario)
            ->findOrFail($idSolicitud);

        $this->chatSolicitudId = $solicitud->id_solicitud;
        $this->nuevoMensaje = '';

        Mensaje::where('id_solicitud', $solicitud->id_solicitud)
            ->where('id_usuario_remitente', '!=', $this->usuario->id_usuario)
            ->whereNull('leido_at')
            ->update(['leido_at' => now()]);

        unset($this->mensajes, $this->noLeidosPorSolicitud);

        Flux::modal('chat-solicitud')->show();
    }

    #[Computed]
    public function mensajes(): Collection
    {
        if (! $this->chatSolicitudId) {
            return collect();
        }

        return Mensaje::where('id_solicitud', $this->chatSolicitudId)
            ->orderBy('fecha_envio')
            ->get();
    }

    #[Computed]
    public function noLeidosPorSolicitud(): Collection
    {
        if (! $this->usuario) {
            return collect();
        }

        return Mensaje::query()
            ->whereIn('id_solicitud', $this->solicitudes->pluck('id_solicitud'))
            ->where('id_usuario_remitente', '!=', $this->usuario->id_usuario)
            ->whereNull('leido_at')
            ->get()
            ->groupBy('id_solicitud')
            ->map(fn ($grupo) => $grupo->count());
    }

    public function enviarMensaje(): void
    {
        $this->validate([
            'nuevoMensaje' => ['required', 'string', 'max:1000'],
        ]);

        $solicitud = SolicitudAdopcion::query()
            ->where('id_usuario', $this->usuario?->id_usuario)
            ->with('mascota.fundacion.usuario')
            ->findOrFail($this->chatSolicitudId);

        $mensaje = Mensaje::create([
            'id_solicitud' => $solicitud->id_solicitud,
            'id_usuario_remitente' => $this->usuario->id_usuario,
            'cuerpo' => $this->nuevoMensaje,
            'fecha_envio' => now(),
        ]);

        $solicitud->mascota->fundacion?->usuario?->user?->notify(new MensajeRecibido($mensaje));

        $this->nuevoMensaje = '';
        unset($this->mensajes);
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

                    <div class="mt-4 flex items-center justify-between">
                        <flux:button size="sm" variant="ghost" wire:click="abrirChat({{ $solicitud->id_solicitud }})">
                            {{ __('Mensajes') }}
                            @if (($this->noLeidosPorSolicitud[$solicitud->id_solicitud] ?? 0) > 0)
                                <span class="ml-1 inline-flex size-5 items-center justify-center rounded-full bg-red-600 text-xs font-semibold text-white">
                                    {{ $this->noLeidosPorSolicitud[$solicitud->id_solicitud] }}
                                </span>
                            @endif
                        </flux:button>

                        @if ($solicitud->estado === 'pendiente')
                            <flux:button
                                size="sm"
                                variant="ghost"
                                wire:click="cancelar({{ $solicitud->id_solicitud }})"
                                wire:confirm="{{ __('¿Cancelar esta solicitud?') }}"
                            >
                                {{ __('Cancelar solicitud') }}
                            </flux:button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <flux:modal name="chat-solicitud" focusable class="max-w-lg">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">{{ __('Mensajes') }}</flux:heading>
                <flux:subheading>{{ __('Conversación con la fundación sobre esta solicitud.') }}</flux:subheading>
            </div>

            <div class="max-h-80 space-y-3 overflow-y-auto rounded-lg bg-neutral-50 p-3">
                @forelse ($this->mensajes as $mensaje)
                    <div class="flex {{ $mensaje->id_usuario_remitente === $this->usuario?->id_usuario ? 'justify-end' : 'justify-start' }}">
                        <div @class([
                            'max-w-[80%] rounded-lg px-3 py-2 text-sm',
                            'bg-[#1f5c47] text-white' => $mensaje->id_usuario_remitente === $this->usuario?->id_usuario,
                            'border border-neutral-200 bg-white text-neutral-700' => $mensaje->id_usuario_remitente !== $this->usuario?->id_usuario,
                        ])>
                            <p>{{ $mensaje->cuerpo }}</p>
                            <p class="mt-1 text-[10px] opacity-70">{{ $mensaje->fecha_envio->translatedFormat('d M, H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-neutral-500">{{ __('Todavía no hay mensajes. Escribí el primero.') }}</p>
                @endforelse
            </div>

            <form wire:submit="enviarMensaje" class="flex items-end gap-2">
                <div class="flex-1">
                    <flux:textarea wire:model="nuevoMensaje" rows="2" :placeholder="__('Escribí un mensaje...')" />
                    @error('nuevoMensaje')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <flux:button type="submit" variant="primary">{{ __('Enviar') }}</flux:button>
            </form>
        </div>
    </flux:modal>
</div>
