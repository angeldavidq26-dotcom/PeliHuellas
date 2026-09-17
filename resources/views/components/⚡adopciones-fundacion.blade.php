<?php

use App\Models\Adopcion;
use App\Models\Fundacion;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Fundacion $fundacion;

    public function mount(Fundacion $fundacion): void
    {
        $this->fundacion = $fundacion;
    }

    #[Computed]
    public function adopciones(): Collection
    {
        return Adopcion::query()
            ->whereHas('solicitud.mascota', fn ($q) => $q->where('id_fundacion', $this->fundacion->id_fundacion))
            ->with(['solicitud.mascota.fotoPrincipal', 'solicitud.usuario'])
            ->latest('fecha_entrega')
            ->get();
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="pf-serif text-3xl font-bold">{{ __('Adopciones') }}</h1>
        <p class="text-neutral-500">{{ __('Seguimiento post-adopción de los animales entregados por :nombre.', ['nombre' => $fundacion->nombre]) }}</p>
    </div>

    @if ($this->adopciones->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('Todavía no hay adopciones completadas.') }}
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Animal') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Adoptante') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Entrega') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Próximo seguimiento') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($this->adopciones as $adopcion)
                        <tr wire:key="adopcion-{{ $adopcion->id }}">
                            <td class="flex items-center gap-3 px-5 py-3">
                                <img
                                    src="{{ $adopcion->solicitud->mascota->fotoPrincipal?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=100&q=80' }}"
                                    alt="{{ $adopcion->solicitud->mascota->nombre }}"
                                    class="size-10 shrink-0 rounded-lg object-cover"
                                >
                                <p class="font-semibold">{{ $adopcion->solicitud->mascota->nombre }}</p>
                            </td>
                            <td class="px-5 py-3 text-neutral-600">
                                {{ $adopcion->solicitud->usuario->nombres }} {{ $adopcion->solicitud->usuario->primer_apellido }}
                            </td>
                            <td class="px-5 py-3 text-neutral-600">
                                {{ \Illuminate\Support\Carbon::parse($adopcion->fecha_entrega)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-3">
                                @if ($adopcion->proximo_seguimiento_at)
                                    <span @class([
                                        'font-medium text-red-600' => $adopcion->proximo_seguimiento_at->isPast(),
                                        'text-neutral-600' => ! $adopcion->proximo_seguimiento_at->isPast(),
                                    ])>
                                        {{ $adopcion->proximo_seguimiento_at->translatedFormat('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <flux:button size="sm" variant="filled" :href="route('fundacion.adopciones.show', $adopcion)" wire:navigate>
                                    {{ __('Ver seguimiento') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
