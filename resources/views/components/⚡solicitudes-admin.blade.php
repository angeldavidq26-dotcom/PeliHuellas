<?php

use App\Models\Adopcion;
use App\Models\Auditoria;
use App\Models\EstadoMascota;
use App\Models\Fundacion;
use App\Models\SolicitudAdopcion;
use App\Models\Usuario;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $estado = 'todos';

    public string $fundacionId = 'todas';

    public bool $soloUrgentes = true;

    public ?int $solicitudId = null;

    public string $nuevoEstado = 'aprobada';

    public string $observaciones = '';

    #[Computed]
    public function fundaciones(): Collection
    {
        return Fundacion::query()->orderBy('nombre')->get(['id_fundacion', 'nombre']);
    }

    #[Computed]
    public function solicitudes(): Collection
    {
        $query = SolicitudAdopcion::query()
            ->with(['mascota.fundacion', 'usuario'])
            ->latest('fecha_solicitud');

        if ($this->estado !== 'todos') {
            $query->where('estado', $this->estado);
        }

        if ($this->fundacionId !== 'todas') {
            $query->whereHas('mascota', fn ($q) => $q->where('id_fundacion', $this->fundacionId));
        }

        if ($this->soloUrgentes) {
            $query->where('estado', 'pendiente')->where('fecha_solicitud', '<=', now()->subDays(5));
        }

        return $query->get();
    }

    public function abrirDecision(int $idSolicitud): void
    {
        $solicitud = SolicitudAdopcion::findOrFail($idSolicitud);

        $this->solicitudId = $solicitud->id_solicitud;
        $this->nuevoEstado = 'aprobada';
        $this->observaciones = (string) $solicitud->observaciones_fundacion;

        Flux::modal('decidir-solicitud-admin')->show();
    }

    public function guardarDecision(): void
    {
        $this->validate([
            'nuevoEstado' => ['required', Rule::in(SolicitudAdopcion::ESTADOS)],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $solicitud = SolicitudAdopcion::findOrFail($this->solicitudId);
        $esResolucion = ! in_array($this->nuevoEstado, ['pendiente', 'en_pausa'], true);
        $usuario = Usuario::paraUser(auth()->user());

        $solicitud->update([
            'estado' => $this->nuevoEstado,
            'observaciones_fundacion' => $this->observaciones ?: null,
            'fecha_resolucion' => $esResolucion ? now() : null,
            'id_usuario_responsable' => $esResolucion ? $usuario?->id_usuario : null,
        ]);

        if ($this->nuevoEstado === 'completada') {
            $this->completarAdopcion($solicitud, $usuario?->id_usuario);
        }

        Auditoria::registrar('solicitud.resuelta_por_admin', $solicitud, $usuario?->id_usuario, ['estado' => $this->nuevoEstado]);

        unset($this->solicitudes);

        Flux::toast(variant: 'success', text: __('Solicitud actualizada.'));

        $this->dispatch('close-modal', name: 'decidir-solicitud-admin');
    }

    protected function completarAdopcion(SolicitudAdopcion $solicitud, ?int $idUsuarioResponsable): void
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
            'id_usuario_responsable' => $idUsuarioResponsable,
        ]);

        Adopcion::firstOrCreate(
            ['id_solicitud' => $solicitud->id_solicitud],
            ['fecha_entrega' => now(), 'proximo_seguimiento_at' => now()->addDays(30)]
        );
    }
}; ?>

<div>
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Solicitudes de adopción') }}</h1>
            <p class="text-neutral-500">{{ __('Vista de supervisión — interviene solo cuando la fundación no pueda responder a tiempo') }}</p>
        </div>
        <p class="text-sm text-neutral-500">{{ trans_choice(':count resultado|:count resultados', $this->solicitudes->count(), ['count' => $this->solicitudes->count()]) }}</p>
    </div>

    <div class="mb-5 flex flex-wrap items-center gap-3">
        <select wire:model.live="estado" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
            <option value="todos">{{ __('Todos los estados') }}</option>
            @foreach (\App\Models\SolicitudAdopcion::ESTADOS as $opcion)
                <option value="{{ $opcion }}">{{ ucfirst(str($opcion)->replace('_', ' ')) }}</option>
            @endforeach
        </select>

        <select wire:model.live="fundacionId" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
            <option value="todas">{{ __('Todas las fundaciones') }}</option>
            @foreach ($this->fundaciones as $fundacion)
                <option value="{{ $fundacion->id_fundacion }}">{{ $fundacion->nombre }}</option>
            @endforeach
        </select>

        <label class="flex items-center gap-2 text-sm text-neutral-700">
            <input type="checkbox" wire:model.live="soloUrgentes" class="rounded border-neutral-300 text-[#1f5c47] focus:ring-[#1f5c47]">
            {{ __('Solo pendientes > 5 días') }}
        </label>
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
                        <th class="px-5 py-3 font-medium">{{ __('Adoptante') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Animal') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Fundación') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Fecha') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Días') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Estado') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($this->solicitudes as $solicitud)
                        @php $dias = $solicitud->fecha_solicitud->diffInDays(now()); @endphp
                        <tr wire:key="sol-{{ $solicitud->id_solicitud }}">
                            <td class="px-5 py-3 font-medium">{{ $solicitud->usuario->nombres }} {{ $solicitud->usuario->primer_apellido }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $solicitud->mascota->nombre }}</td>
                            <td class="px-5 py-3 text-[#1f5c47]">{{ $solicitud->mascota->fundacion->nombre }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $solicitud->fecha_solicitud->format('Y-m-d') }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $dias }}d</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 capitalize">
                                    {{ str($solicitud->estado)->replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <flux:button size="sm" variant="filled" wire:click="abrirDecision({{ $solicitud->id_solicitud }})">
                                    {{ __('Revisar') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <flux:modal name="decidir-solicitud-admin" focusable class="max-w-md">
        <form wire:submit="guardarDecision" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Resolver solicitud') }}</flux:heading>
                <flux:subheading>{{ __('Esta decisión queda registrada como intervención del administrador.') }}</flux:subheading>
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

            <flux:textarea wire:model="observaciones" :label="__('Observaciones (opcional)')" rows="3" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">{{ __('Guardar decisión') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
