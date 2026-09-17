<?php

use App\Models\Fundacion;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function pendientes(): Collection
    {
        return Fundacion::query()
            ->where('estado_verificacion', 'pendiente')
            ->with('sedes')
            ->orderBy('fecha_registro')
            ->get();
    }

    #[Computed]
    public function revisadas(): Collection
    {
        return Fundacion::query()
            ->whereIn('estado_verificacion', ['aprobada', 'rechazada'])
            ->with('sedes')
            ->latest('fecha_registro')
            ->take(15)
            ->get();
    }

    public function decidir(int $idFundacion, string $decision): void
    {
        abort_unless(in_array($decision, ['aprobada', 'rechazada'], true), 422);

        $fundacion = Fundacion::query()->where('estado_verificacion', 'pendiente')->findOrFail($idFundacion);
        $fundacion->update(['estado_verificacion' => $decision]);

        unset($this->pendientes, $this->revisadas);

        Flux::toast(
            variant: $decision === 'aprobada' ? 'success' : 'danger',
            text: $decision === 'aprobada'
                ? __(':nombre fue aprobada.', ['nombre' => $fundacion->nombre])
                : __(':nombre fue rechazada.', ['nombre' => $fundacion->nombre]),
        );
    }
}; ?>

<div>
    <div class="mb-8">
        <p class="mb-1 text-xs font-semibold tracking-widest text-[#1f5c47] uppercase">{{ __('Panel interno') }}</p>
        <h1 class="pf-serif text-3xl font-bold">{{ __('Solicitudes de fundaciones') }}</h1>
        <p class="text-neutral-500">{{ __('Revisá cada solicitud y decidí si la fundación queda aprobada para publicar animales.') }}</p>
    </div>

    <h2 class="pf-serif mb-3 text-lg font-bold">
        {{ __('Pendientes') }}
        <span class="ml-1 text-sm font-normal text-neutral-400">({{ $this->pendientes->count() }})</span>
    </h2>

    @if ($this->pendientes->isEmpty())
        <div class="mb-10 rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center text-neutral-500">
            {{ __('No hay solicitudes pendientes por revisar.') }}
        </div>
    @else
        <div class="mb-10 space-y-4">
            @foreach ($this->pendientes as $fundacion)
                <div wire:key="pendiente-{{ $fundacion->id_fundacion }}" class="rounded-xl border border-amber-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold">{{ $fundacion->nombre }}</h3>
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">{{ __('Pendiente') }}</span>
                            </div>
                            <p class="text-sm text-neutral-500">
                                {{ __('NIT') }} {{ $fundacion->nit }} · {{ $fundacion->sedes->first()?->ciudad }} · {{ $fundacion->correo }} · {{ $fundacion->telefono }}
                            </p>
                            @if ($fundacion->capacidad)
                                <p class="text-sm text-neutral-500">{{ __('Capacidad aprox.: :n animales', ['n' => $fundacion->capacidad]) }}</p>
                            @endif
                            @if ($fundacion->descripcion)
                                <p class="mt-2 max-w-2xl text-sm text-neutral-700">{{ $fundacion->descripcion }}</p>
                            @endif
                            <p class="mt-2 text-xs text-neutral-400">
                                {{ __('Recibida el :fecha', ['fecha' => $fundacion->fecha_registro->translatedFormat('d M Y, H:i')]) }}
                            </p>
                        </div>

                        <div class="flex shrink-0 gap-2">
                            <flux:button
                                size="sm"
                                variant="danger"
                                wire:click="decidir({{ $fundacion->id_fundacion }}, 'rechazada')"
                                wire:confirm="{{ __('¿Rechazar la solicitud de :nombre?', ['nombre' => $fundacion->nombre]) }}"
                            >
                                {{ __('Rechazar') }}
                            </flux:button>
                            <flux:button
                                size="sm"
                                variant="primary"
                                wire:click="decidir({{ $fundacion->id_fundacion }}, 'aprobada')"
                            >
                                {{ __('Aprobar') }}
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="pf-serif mb-3 text-lg font-bold">{{ __('Revisadas recientemente') }}</h2>

    @if ($this->revisadas->isEmpty())
        <p class="text-sm text-neutral-500">{{ __('Todavía no revisaste ninguna solicitud.') }}</p>
    @else
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Fundación') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Ciudad') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Estado') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($this->revisadas as $fundacion)
                        <tr wire:key="revisada-{{ $fundacion->id_fundacion }}">
                            <td class="px-5 py-3 font-medium">{{ $fundacion->nombre }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->sedes->first()?->ciudad }}</td>
                            <td class="px-5 py-3">
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-[#dcece4] text-[#234a3a]' => $fundacion->estado_verificacion === 'aprobada',
                                    'bg-red-100 text-red-700' => $fundacion->estado_verificacion === 'rechazada',
                                ])>
                                    {{ $fundacion->estado_verificacion === 'aprobada' ? __('Aprobada') : __('Rechazada') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
