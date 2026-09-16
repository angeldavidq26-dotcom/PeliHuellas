<x-fundacion-shell :fundacion="$fundacion" active="panel">
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Panel de la fundación') }}</h1>
            <p class="text-neutral-500">{{ $fundacion->nombre }} · {{ $fundacion->sedes->first()?->ciudad }}</p>
        </div>
        <flux:button :href="route('fundacion.publicar')" variant="primary" icon="plus" wire:navigate>
            {{ __('Publicar animal') }}
        </flux:button>
    </div>

    @if ($stats['en_espera'] > 0)
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            <flux:icon name="shield-exclamation" variant="micro" class="size-5 shrink-0 text-amber-600" />
            <p class="flex-1">
                {{ trans_choice('Tenés :count animal esperando que actualices su estado.|Tenés :count animales esperando que actualices su estado.', $stats['en_espera'], ['count' => $stats['en_espera']]) }}
            </p>
            <a href="{{ route('fundacion.mis-animales') }}" class="flex items-center gap-1 font-semibold whitespace-nowrap" wire:navigate>
                {{ __('Revisar ahora') }} →
            </a>
        </div>
    @endif

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-5">
            <span class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-[#dcece4] text-[#1f5c47]">
                <flux:icon name="map-pin" variant="micro" class="size-5" />
            </span>
            <p class="pf-serif text-2xl font-bold">{{ $stats['disponibles'] }}</p>
            <p class="text-sm text-neutral-500">{{ __('Animales disponibles') }}</p>
        </div>
        <div class="rounded-xl border border-neutral-200 bg-white p-5">
            <span class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                <flux:icon name="shield-exclamation" variant="micro" class="size-5" />
            </span>
            <p class="pf-serif text-2xl font-bold">{{ $stats['en_espera'] }}</p>
            <p class="text-sm text-neutral-500">{{ __('En espera') }}</p>
        </div>
        <div class="rounded-xl border border-neutral-200 bg-white p-5">
            <span class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-[#dcece4] text-[#1f5c47]">
                <flux:icon name="document-text" variant="micro" class="size-5" />
            </span>
            <p class="pf-serif text-2xl font-bold">{{ $stats['solicitudes_sin_revisar'] }}</p>
            <p class="text-sm text-neutral-500">{{ __('Solicitudes sin revisar') }}</p>
        </div>
        <div class="rounded-xl border border-neutral-200 bg-white p-5">
            <span class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-[#dcece4] text-[#1f5c47]">
                <flux:icon name="heart" variant="micro" class="size-5" />
            </span>
            <p class="pf-serif text-2xl font-bold">{{ $stats['adopciones_del_mes'] }}</p>
            <p class="text-sm text-neutral-500">{{ __('Adopciones del mes') }}</p>
        </div>
    </div>

    <div class="mb-8">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="pf-serif text-xl font-bold">{{ __('Últimas solicitudes') }}</h2>
            <a href="{{ route('fundacion.solicitudes') }}" class="text-sm font-medium text-[#1f5c47]" wire:navigate>{{ __('Ver todas →') }}</a>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
            @if ($ultimasSolicitudes->isEmpty())
                <p class="p-6 text-center text-sm text-neutral-500">{{ __('Todavía no recibiste solicitudes de adopción.') }}</p>
            @else
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                        <tr>
                            <th class="px-5 py-3 font-medium">{{ __('Animal') }}</th>
                            <th class="px-5 py-3 font-medium">{{ __('Solicitante') }}</th>
                            <th class="px-5 py-3 font-medium">{{ __('Fecha') }}</th>
                            <th class="px-5 py-3 font-medium">{{ __('Estado') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach ($ultimasSolicitudes as $solicitud)
                            <tr>
                                <td class="px-5 py-3 font-medium">{{ $solicitud->animal }}</td>
                                <td class="px-5 py-3 text-neutral-600">{{ $solicitud->solicitante_nombres }} {{ $solicitud->solicitante_apellido }}</td>
                                <td class="px-5 py-3 text-neutral-600">{{ \Illuminate\Support\Carbon::parse($solicitud->fecha_solicitud)->translatedFormat('d M') }}</td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full bg-[#dcece4] px-2.5 py-1 text-xs font-semibold text-[#234a3a] capitalize">{{ $solicitud->estado }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div>
        <div class="mb-3 flex items-center justify-between">
            <h2 class="pf-serif text-xl font-bold">{{ __('Mis animales') }}</h2>
            <a href="{{ route('fundacion.mis-animales') }}" class="text-sm font-medium text-[#1f5c47]" wire:navigate>{{ __('Ver todos →') }}</a>
        </div>

        @if ($misAnimales->isEmpty())
            <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-8 text-center text-sm text-neutral-500">
                {{ __('Todavía no publicaste ningún animal.') }}
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ($misAnimales as $mascota)
                    <a href="{{ route('fundacion.mis-animales') }}" class="block overflow-hidden rounded-xl border border-neutral-200 bg-white" wire:navigate>
                        <div class="aspect-square">
                            <img
                                src="{{ $mascota->fotoPrincipal?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=300&q=80' }}"
                                alt="{{ $mascota->nombre }}"
                                class="h-full w-full object-cover"
                            >
                        </div>
                        <div class="p-3">
                            <p class="font-semibold">{{ $mascota->nombre }}</p>
                            <p class="mb-2 text-xs text-neutral-500">{{ $mascota->raza?->nombre_raza ?? ucfirst($mascota->especie) }}</p>
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-semibold',
                                'bg-[#dcece4] text-[#234a3a]' => $mascota->estado === 'disponible',
                                'bg-amber-100 text-amber-700' => $mascota->estado === 'espera',
                                'bg-neutral-100 text-neutral-600' => $mascota->estado === 'adoptado',
                            ])>
                                {{ ucfirst($mascota->estado) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-fundacion-shell>
