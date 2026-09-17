<?php

use App\Models\Adopcion;
use App\Models\Auditoria;
use App\Models\EstadoMascota;
use App\Models\Fundacion;
use App\Models\Mensaje;
use App\Models\SolicitudAdopcion;
use App\Notifications\MensajeRecibido;
use App\Notifications\SolicitudAdopcionResuelta;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Fundacion $fundacion;

    public string $filtro = 'pendiente';

    public ?int $solicitudId = null;

    public string $nuevoEstado = 'aprobada';

    public string $observaciones = '';

    public ?int $chatSolicitudId = null;

    public string $nuevoMensaje = '';

    public function mount(Fundacion $fundacion): void
    {
        $this->fundacion = $fundacion;
    }

    #[Computed]
    public function solicitudes(): Collection
    {
        $query = SolicitudAdopcion::query()
            ->whereHas('mascota', fn ($q) => $q->where('id_fundacion', $this->fundacion->id_fundacion))
            ->with(['mascota', 'usuario'])
            ->latest('fecha_solicitud');

        if ($this->filtro !== 'todas') {
            $query->where('estado', $this->filtro);
        }

        return $query->get();
    }

    public function abrirDecision(int $idSolicitud): void
    {
        $solicitud = $this->solicitudDe($idSolicitud);

        $this->solicitudId = $solicitud->id_solicitud;
        $this->nuevoEstado = 'aprobada';
        $this->observaciones = (string) $solicitud->observaciones_fundacion;

        Flux::modal('decidir-solicitud')->show();
    }

    public function guardarDecision(): void
    {
        $this->validate([
            'nuevoEstado' => ['required', Rule::in(SolicitudAdopcion::ESTADOS)],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $solicitud = $this->solicitudDe($this->solicitudId);
        $esResolucion = ! in_array($this->nuevoEstado, ['pendiente', 'en_pausa'], true);

        $solicitud->update([
            'estado' => $this->nuevoEstado,
            'observaciones_fundacion' => $this->observaciones ?: null,
            'fecha_resolucion' => $esResolucion ? now() : null,
            'id_usuario_responsable' => $esResolucion ? $this->fundacion->id_usuario : null,
        ]);

        if ($this->nuevoEstado === 'completada') {
            $this->completarAdopcion($solicitud);
        }

        Auditoria::registrar('solicitud.resuelta', $solicitud, $this->fundacion->id_usuario, ['estado' => $this->nuevoEstado]);

        if ($esResolucion) {
            $solicitud->usuario?->user?->notify(new SolicitudAdopcionResuelta($solicitud));
        }

        unset($this->solicitudes);

        Flux::toast(variant: 'success', text: __('Solicitud actualizada.'));

        $this->dispatch('close-modal', name: 'decidir-solicitud');
    }

    protected function completarAdopcion(SolicitudAdopcion $solicitud): void
    {
        $mascota = $solicitud->mascota;
        $estadoAnterior = $mascota->estado;

        $mascota->update([
            'estado' => 'adoptado',
            'fecha_retiro' => now(),
        ]);

        EstadoMascota::create([
            'id_mascota' => $mascota->id_mascota,
            'estado_anterior' => $estadoAnterior,
            'estado' => 'adoptado',
            'motivo' => __('Adopción completada'),
            'fecha_cambio' => now(),
            'id_usuario_responsable' => $this->fundacion->id_usuario,
        ]);

        Adopcion::firstOrCreate(
            ['id_solicitud' => $solicitud->id_solicitud],
            ['fecha_entrega' => now(), 'proximo_seguimiento_at' => now()->addDays(30)]
        );
    }

    protected function solicitudDe(int $id): SolicitudAdopcion
    {
        return SolicitudAdopcion::query()
            ->whereHas('mascota', fn ($q) => $q->where('id_fundacion', $this->fundacion->id_fundacion))
            ->findOrFail($id);
    }

    public function abrirChat(int $idSolicitud): void
    {
        $solicitud = $this->solicitudDe($idSolicitud);

        $this->chatSolicitudId = $solicitud->id_solicitud;
        $this->nuevoMensaje = '';

        Mensaje::where('id_solicitud', $solicitud->id_solicitud)
            ->where('id_usuario_remitente', '!=', $this->fundacion->id_usuario)
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
        return Mensaje::query()
            ->whereIn('id_solicitud', $this->solicitudes->pluck('id_solicitud'))
            ->where('id_usuario_remitente', '!=', $this->fundacion->id_usuario)
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

        $solicitud = $this->solicitudDe($this->chatSolicitudId);

        $mensaje = Mensaje::create([
            'id_solicitud' => $solicitud->id_solicitud,
            'id_usuario_remitente' => $this->fundacion->id_usuario,
            'cuerpo' => $this->nuevoMensaje,
            'fecha_envio' => now(),
        ]);

        $solicitud->usuario?->user?->notify(new MensajeRecibido($mensaje));

        $this->nuevoMensaje = '';
        unset($this->mensajes);
    }
}; ?>

<div>
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Solicitudes') }}</h1>
            <p class="text-neutral-500">{{ __('Solicitudes de adopción para los animales de :nombre.', ['nombre' => $fundacion->nombre]) }}</p>
        </div>
        <select wire:model.live="filtro" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
            <option value="pendiente">{{ __('Pendientes') }}</option>
            <option value="en_pausa">{{ __('En pausa') }}</option>
            <option value="aprobada">{{ __('Aprobadas') }}</option>
            <option value="rechazada">{{ __('Rechazadas') }}</option>
            <option value="completada">{{ __('Completadas') }}</option>
            <option value="todas">{{ __('Todas') }}</option>
        </select>
    </div>

    @if ($this->solicitudes->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('No hay solicitudes en este filtro.') }}
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Animal') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Solicitante') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Fecha') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Estado') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($this->solicitudes as $solicitud)
                        <tr wire:key="sol-{{ $solicitud->id_solicitud }}">
                            <td class="px-5 py-3 font-medium">{{ $solicitud->mascota->nombre }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $solicitud->usuario->nombres }} {{ $solicitud->usuario->primer_apellido }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $solicitud->fecha_solicitud->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full bg-[#dcece4] px-2.5 py-1 text-xs font-semibold text-[#234a3a] capitalize">
                                    {{ str($solicitud->estado)->replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button size="sm" variant="ghost" wire:click="abrirChat({{ $solicitud->id_solicitud }})">
                                        {{ __('Mensajes') }}
                                        @if (($this->noLeidosPorSolicitud[$solicitud->id_solicitud] ?? 0) > 0)
                                            <span class="ml-1 inline-flex size-5 items-center justify-center rounded-full bg-red-600 text-xs font-semibold text-white">
                                                {{ $this->noLeidosPorSolicitud[$solicitud->id_solicitud] }}
                                            </span>
                                        @endif
                                    </flux:button>
                                    <flux:button size="sm" variant="filled" wire:click="abrirDecision({{ $solicitud->id_solicitud }})">
                                        {{ __('Decidir') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <flux:modal name="decidir-solicitud" focusable class="max-w-md">
        <form wire:submit="guardarDecision" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Decidir solicitud') }}</flux:heading>
                <flux:subheading>{{ __('Esto lo va a ver la persona que solicitó la adopción.') }}</flux:subheading>
            </div>

            <div>
                <flux:label>{{ __('Nuevo estado') }}</flux:label>
                <select wire:model="nuevoEstado" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                    <option value="aprobada">{{ __('Aprobada') }}</option>
                    <option value="en_pausa">{{ __('En pausa') }}</option>
                    <option value="rechazada">{{ __('Rechazada') }}</option>
                    <option value="completada">{{ __('Completada') }}</option>
                    <option value="no_concretada">{{ __('No concretada') }}</option>
                </select>
            </div>

            <flux:textarea wire:model="observaciones" :label="__('Observaciones (opcional)')" :description="__('Por ejemplo, por qué quedó en pausa o rechazada.')" rows="3" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">{{ __('Guardar decisión') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="chat-solicitud" focusable class="max-w-lg">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">{{ __('Mensajes') }}</flux:heading>
                <flux:subheading>{{ __('Conversación con el adoptante sobre esta solicitud.') }}</flux:subheading>
            </div>

            <div class="max-h-80 space-y-3 overflow-y-auto rounded-lg bg-neutral-50 p-3">
                @forelse ($this->mensajes as $mensaje)
                    <div class="flex {{ $mensaje->id_usuario_remitente === $fundacion->id_usuario ? 'justify-end' : 'justify-start' }}">
                        <div @class([
                            'max-w-[80%] rounded-lg px-3 py-2 text-sm',
                            'bg-[#1f5c47] text-white' => $mensaje->id_usuario_remitente === $fundacion->id_usuario,
                            'border border-neutral-200 bg-white text-neutral-700' => $mensaje->id_usuario_remitente !== $fundacion->id_usuario,
                        ])>
                            <p>{{ $mensaje->cuerpo }}</p>
                            <p class="mt-1 text-[10px] opacity-70">{{ $mensaje->fecha_envio->translatedFormat('d M, H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-neutral-500">{{ __('Todavía no hay mensajes.') }}</p>
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
