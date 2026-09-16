<x-fundacion-shell :fundacion="$fundacion" active="mis-animales">
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Mis animales') }}</h1>
            <p class="text-neutral-500">{{ __(':count animales publicados', ['count' => $mascotas->count()]) }}</p>
        </div>
        <flux:button :href="route('fundacion.publicar')" variant="primary" icon="plus" wire:navigate>
            {{ __('Publicar animal') }}
        </flux:button>
    </div>

    @if ($mascotas->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('Todavía no publicaste ningún animal. Empezá con el botón "Publicar animal".') }}
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($mascotas as $mascota)
                <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
                    <div class="relative aspect-square">
                        <img
                            src="{{ $mascota->fotoPrincipal?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=500&q=80' }}"
                            alt="{{ $mascota->nombre }}"
                            class="h-full w-full object-cover"
                        >
                        <span @class([
                            'absolute top-3 left-3 rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-[#1f5c47] text-white' => $mascota->estado === 'disponible',
                            'bg-amber-500 text-white' => $mascota->estado === 'espera',
                            'bg-white text-neutral-700 shadow' => $mascota->estado === 'adoptado',
                        ])>
                            {{ ucfirst($mascota->estado) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="mb-1 flex items-start justify-between gap-2">
                            <h3 class="font-semibold">{{ $mascota->nombre }}</h3>
                            <span class="text-xs text-neutral-500">
                                {{ $mascota->sexo === 'macho' ? __('Macho') : __('Hembra') }} · {{ $mascota->tamanoTexto() }}
                            </span>
                        </div>
                        <p class="text-sm text-neutral-500">
                            {{ ucfirst($mascota->especie) }}{{ $mascota->raza?->nombre_raza ? ' '.$mascota->raza->nombre_raza : '' }}
                            @if ($mascota->edadTexto())
                                · {{ $mascota->edadTexto() }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-fundacion-shell>
