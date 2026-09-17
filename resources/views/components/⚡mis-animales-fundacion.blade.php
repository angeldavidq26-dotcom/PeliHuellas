<?php

use App\Models\EstadoMascota;
use App\Models\Fundacion;
use App\Models\Mascota;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Fundacion $fundacion;

    public string $filtro = 'todas';

    public ?int $mascotaEstadoId = null;

    public string $nuevoEstado = 'disponible';

    public string $motivoCambio = '';

    public ?int $mascotaHistorialId = null;

    public function mount(Fundacion $fundacion): void
    {
        $this->fundacion = $fundacion;
    }

    #[Computed]
    public function mascotas(): Collection
    {
        $query = $this->fundacion->mascotas()->with(['fotoPrincipal', 'raza', 'sede'])->latest('fecha_ingreso');

        if (in_array($this->filtro, ['disponible', 'espera', 'adoptado'], true)) {
            $query->where('estado', $this->filtro);
        }

        $mascotas = $query->get();

        if ($mascotas->isEmpty()) {
            return $mascotas;
        }

        $ids = $mascotas->pluck('id_mascota');

        $solicitudes = DB::table('solicitud_adopcion')
            ->select('id_mascota', DB::raw('count(*) as total'))
            ->whereIn('id_mascota', $ids)
            ->groupBy('id_mascota')
            ->pluck('total', 'id_mascota');

        $ultimoCambio = DB::table('estado_mascota')
            ->select('id_mascota', DB::raw('MAX(fecha_cambio) as ultimo'))
            ->whereIn('id_mascota', $ids)
            ->groupBy('id_mascota')
            ->pluck('ultimo', 'id_mascota');

        return $mascotas->map(function (Mascota $mascota) use ($solicitudes, $ultimoCambio) {
            $mascota->solicitudes_count = (int) ($solicitudes[$mascota->id_mascota] ?? 0);

            $desde = $ultimoCambio[$mascota->id_mascota] ?? $mascota->fecha_ingreso;
            $mascota->dias_en_estado = $desde ? (int) now()->diffInDays($desde, absolute: true) : 0;

            return $mascota;
        });
    }

    public function abrirCambioEstado(int $idMascota): void
    {
        $mascota = $this->fundacion->mascotas()->findOrFail($idMascota);

        $this->mascotaEstadoId = $mascota->id_mascota;
        $this->nuevoEstado = $mascota->estado;
        $this->motivoCambio = '';

        Flux::modal('cambiar-estado')->show();
    }

    public function guardarEstado(): void
    {
        $this->validate([
            'nuevoEstado' => ['required', Rule::in(['disponible', 'espera', 'adoptado'])],
            'motivoCambio' => ['nullable', 'string', 'max:300'],
        ]);

        $mascota = $this->fundacion->mascotas()->findOrFail($this->mascotaEstadoId);
        $anterior = $mascota->estado;

        if ($anterior !== $this->nuevoEstado) {
            DB::transaction(function () use ($mascota, $anterior): void {
                $mascota->update([
                    'estado' => $this->nuevoEstado,
                    'fecha_retiro' => $this->nuevoEstado === 'adoptado' ? now() : null,
                ]);

                EstadoMascota::create([
                    'id_mascota' => $mascota->id_mascota,
                    'estado_anterior' => $anterior,
                    'estado' => $this->nuevoEstado,
                    'motivo' => $this->motivoCambio !== '' ? $this->motivoCambio : null,
                    'fecha_cambio' => now(),
                    'id_usuario_responsable' => $this->fundacion->id_usuario,
                ]);
            });

            unset($this->mascotas);
            Flux::toast(variant: 'success', text: __(':nombre ahora está en estado ":estado".', ['nombre' => $mascota->nombre, 'estado' => ucfirst($this->nuevoEstado)]));
        }

        $this->dispatch('close-modal', name: 'cambiar-estado');
    }

    public function abrirHistorial(int $idMascota): void
    {
        $this->mascotaHistorialId = $idMascota;

        Flux::modal('historial-mascota')->show();
    }

    #[Computed]
    public function historial(): Collection
    {
        if (! $this->mascotaHistorialId) {
            return collect();
        }

        return EstadoMascota::query()
            ->where('id_mascota', $this->mascotaHistorialId)
            ->orderByDesc('fecha_cambio')
            ->get();
    }

    #[Computed]
    public function mascotaHistorial(): ?Mascota
    {
        return $this->mascotaHistorialId
            ? $this->mascotas->firstWhere('id_mascota', $this->mascotaHistorialId)
            : null;
    }
}; ?>

<div>
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Mis animales') }}</h1>
            <p class="text-neutral-500">{{ __(':count animales publicados', ['count' => $this->mascotas->count()]) }}</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="filtro" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                <option value="todas">{{ __('Todas') }}</option>
                <option value="disponible">{{ __('Disponibles') }}</option>
                <option value="espera">{{ __('En espera') }}</option>
                <option value="adoptado">{{ __('Adoptados') }}</option>
            </select>
            <flux:button :href="route('fundacion.publicar')" variant="primary" icon="plus" wire:navigate>
                {{ __('Publicar animal') }}
            </flux:button>
        </div>
    </div>

    @if ($this->mascotas->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('Todavía no publicaste ningún animal. Empezá con el botón "Publicar animal".') }}
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Animal') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Publicado') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Estado') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Solicitudes') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Sede') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($this->mascotas as $mascota)
                        <tr wire:key="mascota-{{ $mascota->id_mascota }}">
                            <td class="flex items-center gap-3 px-5 py-3">
                                <img
                                    src="{{ $mascota->fotoPrincipal?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=100&q=80' }}"
                                    alt="{{ $mascota->nombre }}"
                                    class="size-10 shrink-0 rounded-lg object-cover"
                                >
                                <div>
                                    <p class="font-semibold">{{ $mascota->nombre }}</p>
                                    <p class="text-xs text-neutral-500">
                                        {{ ucfirst($mascota->especie) }}{{ $mascota->raza?->nombre_raza ? ' · '.$mascota->raza->nombre_raza : '' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-neutral-600">
                                {{ \Illuminate\Support\Carbon::parse($mascota->fecha_ingreso)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-3">
                                <span @class([
                                    'inline-block rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-[#dcece4] text-[#234a3a]' => $mascota->estado === 'disponible',
                                    'bg-amber-100 text-amber-700' => $mascota->estado === 'espera',
                                    'bg-neutral-100 text-neutral-600' => $mascota->estado === 'adoptado',
                                ])>
                                    {{ ucfirst($mascota->estado) }}
                                    @if ($mascota->estado === 'espera')
                                        · {{ $mascota->dias_en_estado }}d
                                    @endif
                                </span>
                                @if ($mascota->estado === 'espera' && $mascota->dias_en_estado >= 3)
                                    <p class="mt-1 text-xs font-medium text-red-600">{{ __('Requiere atención') }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-neutral-600">
                                {{ $mascota->solicitudes_count > 0 ? trans_choice(':count solicitud|:count solicitudes', $mascota->solicitudes_count, ['count' => $mascota->solicitudes_count]) : '—' }}
                            </td>
                            <td class="px-5 py-3 text-neutral-600">{{ $mascota->sede?->nombre ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button size="sm" variant="filled" wire:click="abrirCambioEstado({{ $mascota->id_mascota }})">
                                        {{ __('Cambiar estado') }}
                                    </flux:button>
                                    <flux:button size="sm" variant="filled" :href="route('fundacion.mis-animales.editar', $mascota)" wire:navigate>
                                        {{ __('Editar') }}
                                    </flux:button>
                                    <flux:button size="sm" variant="ghost" wire:click="abrirHistorial({{ $mascota->id_mascota }})">
                                        {{ __('Historial') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <flux:modal name="cambiar-estado" focusable class="max-w-md">
        <form wire:submit="guardarEstado" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Cambiar estado') }}</flux:heading>
                <flux:subheading>{{ __('Actualizá el estado de este animal. Queda registrado en su historial.') }}</flux:subheading>
            </div>

            <div class="space-y-4">
                <div>
                    <flux:label>{{ __('Nuevo estado') }}</flux:label>
                    <div class="mt-2 flex gap-2">
                        @foreach (['disponible' => __('Disponible'), 'espera' => __('En espera'), 'adoptado' => __('Adoptado')] as $value => $label)
                            <label class="relative flex-1">
                                <input type="radio" wire:model="nuevoEstado" value="{{ $value }}" class="peer sr-only">
                                <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <flux:textarea wire:model="motivoCambio" :label="__('Motivo (opcional)')" rows="3" />
            </div>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">{{ __('Guardar estado') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="historial-mascota" focusable class="max-w-lg">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">
                    {{ __('Historial') }} @if ($this->mascotaHistorial) — {{ $this->mascotaHistorial->nombre }} @endif
                </flux:heading>
                <flux:subheading>{{ __('Cambios de estado registrados para este animal.') }}</flux:subheading>
            </div>

            @if ($this->historial->isEmpty())
                <p class="text-sm text-neutral-500">{{ __('Todavía no hay cambios de estado registrados.') }}</p>
            @else
                <ul class="max-h-80 space-y-3 overflow-y-auto">
                    @foreach ($this->historial as $cambio)
                        <li class="rounded-lg border border-neutral-200 p-3 text-sm">
                            <p class="font-medium">
                                {{ ucfirst($cambio->estado_anterior) }} → {{ ucfirst($cambio->estado) }}
                            </p>
                            <p class="text-xs text-neutral-500">
                                {{ \Illuminate\Support\Carbon::parse($cambio->fecha_cambio)->translatedFormat('d M Y, H:i') }}
                            </p>
                            @if ($cambio->motivo)
                                <p class="mt-1 text-neutral-600">{{ $cambio->motivo }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="flex justify-end">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cerrar') }}</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>
