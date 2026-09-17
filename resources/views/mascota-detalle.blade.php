<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-neutral-50 text-neutral-900 antialiased">
        <x-site-header :guest-actions="false" />

        <div class="mx-auto max-w-7xl px-6 py-8">
            <nav class="mb-6 text-sm text-neutral-500">
                <a href="{{ route('home') }}" class="hover:text-neutral-700" wire:navigate>{{ __('Inicio') }}</a>
                <span class="mx-1.5">/</span>
                <a href="{{ route('mascotas') }}" class="hover:text-neutral-700" wire:navigate>{{ __('Adopción') }}</a>
                <span class="mx-1.5">/</span>
                <span class="text-neutral-900">{{ $mascota->nombre }}</span>
            </nav>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    @php
                        $fotos = $mascota->fotos->isNotEmpty()
                            ? $mascota->fotos
                            : collect([(object) ['url' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1000&q=80']]);
                    @endphp

                    <div x-data="{ activa: '{{ $fotos->first()->url }}' }">
                        <div class="aspect-video overflow-hidden rounded-xl bg-neutral-200">
                            <img :src="activa" alt="{{ $mascota->nombre }}" class="h-full w-full object-cover">
                        </div>

                        @if ($fotos->count() > 1)
                            <div class="mt-4 flex flex-wrap gap-3">
                                @foreach ($fotos as $foto)
                                    <button
                                        type="button"
                                        x-on:click="activa = '{{ $foto->url }}'"
                                        class="size-16 overflow-hidden rounded-lg border-2"
                                        x-bind:class="activa === '{{ $foto->url }}' ? 'border-[#1f5c47]' : 'border-transparent'"
                                    >
                                        <img src="{{ $foto->url }}" alt="" class="h-full w-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <h1 class="pf-serif text-3xl font-bold">{{ $mascota->nombre }}</h1>
                        <span @class([
                            'rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-[#1f5c47] text-white' => $mascota->estado === 'disponible',
                            'bg-amber-100 text-amber-700' => $mascota->estado === 'espera',
                            'bg-neutral-200 text-neutral-700' => $mascota->estado === 'adoptado',
                        ])>
                            {{ ucfirst($mascota->estado) }}
                        </span>
                    </div>

                    <p class="mt-2 flex flex-wrap gap-x-1.5 text-sm text-neutral-500">
                        <span>{{ ucfirst($mascota->especie) }}</span>
                        @if ($mascota->raza) <span>· {{ $mascota->raza->nombre_raza }}</span> @endif
                        @if ($mascota->edadTexto()) <span>· {{ $mascota->edadTexto() }}</span> @endif
                        <span>· {{ $mascota->sexo === 'macho' ? __('Macho') : __('Hembra') }}</span>
                        <span>· {{ $mascota->tamanoTexto() }}</span>
                        @if ($mascota->esterilizado) <span>· {{ __('Esterilizado') }}</span> @endif
                        @if ($mascota->vacunado) <span>· {{ __('Vacunas al día') }}</span> @endif
                    </p>

                    @if ($mascota->caracteristicas->isNotEmpty())
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($mascota->caracteristicas as $tag)
                                <span class="rounded-full bg-[#dcece4] px-3 py-1 text-xs font-medium text-[#234a3a]">{{ $tag->nombre }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-8">
                        <h2 class="pf-serif mb-2 text-lg font-bold">{{ __('Sobre :nombre', ['nombre' => $mascota->nombre]) }}</h2>
                        <p class="text-sm leading-relaxed text-neutral-700">
                            {{ $mascota->descripcion ?: __('Esta fundación todavía no agregó una descripción para :nombre.', ['nombre' => $mascota->nombre]) }}
                        </p>
                    </div>

                    <div class="mt-8">
                        <h2 class="pf-serif mb-2 text-lg font-bold">{{ __('Dónde encontrarnos') }}</h2>
                        <div class="flex aspect-[3/1] flex-col items-center justify-center gap-1 rounded-xl bg-[#eef2f0] text-neutral-600">
                            <flux:icon name="map-pin" variant="micro" class="size-5" />
                            <p class="text-sm">
                                {{ $mascota->sede?->ciudad ?? $mascota->fundacion?->sedes->first()?->ciudad }}
                                @if ($mascota->fundacion) · {{ $mascota->fundacion->nombre }} @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="sticky top-8 rounded-xl border border-neutral-200 bg-white p-5">
                        @if ($mascota->fundacion)
                            <div class="mb-4 flex items-center gap-3 border-b border-neutral-100 pb-4">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#dcece4] text-[#1f5c47]">
                                    <flux:icon name="home" variant="micro" class="size-5" />
                                </span>
                                <div>
                                    <p class="font-semibold">{{ $mascota->fundacion->nombre }}</p>
                                    <p class="flex items-center gap-1.5 text-xs text-neutral-500">
                                        @if ($mascota->fundacion->estado_verificacion === 'aprobada')
                                            <span class="font-medium text-[#1f5c47]">{{ __('Verificada') }}</span>
                                            <span>·</span>
                                        @endif
                                        {{ $mascota->sede?->ciudad ?? $mascota->fundacion->sedes->first()?->ciudad }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="mb-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-neutral-400 uppercase">{{ __('Especie') }}</p>
                                <p class="font-semibold">{{ ucfirst($mascota->especie) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-400 uppercase">{{ __('Raza') }}</p>
                                <p class="font-semibold">{{ $mascota->raza?->nombre_raza ?? __('Mestizo') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-400 uppercase">{{ __('Edad') }}</p>
                                <p class="font-semibold">{{ $mascota->edadTexto() ?: '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-400 uppercase">{{ __('Tamaño') }}</p>
                                <p class="font-semibold">{{ $mascota->tamanoTexto() }}</p>
                            </div>
                        </div>

                        <livewire:solicitar-adopcion :mascota="$mascota" />

                        @if ($mascota->fundacion?->correo)
                            <flux:button
                                href="mailto:{{ $mascota->fundacion->correo }}?subject={{ urlencode(__('Consulta sobre :nombre', ['nombre' => $mascota->nombre])) }}"
                                variant="filled"
                                class="mt-3 w-full"
                            >
                                {{ __('Escribir a la fundación') }}
                            </flux:button>
                        @endif
                    </div>

                    <a href="{{ route('mascotas') }}" class="mt-4 flex items-center gap-1 text-sm text-neutral-500 hover:text-neutral-700" wire:navigate>
                        <flux:icon name="chevron-left" variant="micro" class="size-4" />
                        {{ __('Volver al catálogo') }}
                    </a>
                </div>
            </div>
        </div>

        @include('partials.home.footer')

        @fluxScripts
    </body>
</html>
