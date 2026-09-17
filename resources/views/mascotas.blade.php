<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-neutral-50 text-neutral-900 antialiased">
        <x-site-header :guest-actions="false" />

        <form method="GET" action="{{ route('mascotas') }}">
            <div class="mx-auto max-w-7xl px-6 py-10">
                <p class="mb-1 text-xs font-semibold tracking-widest text-[#1f5c47] uppercase">{{ __('Adopción') }}</p>
                <h1 class="pf-serif mb-8 text-3xl font-bold sm:text-4xl">{{ __('Animales en adopción') }}</h1>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                    <aside class="space-y-8 lg:col-span-1">
                        <div>
                            <h2 class="mb-3 text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Especie') }}</h2>
                            <div class="space-y-2">
                                @foreach (['' => __('Todos'), 'perro' => __('Perro'), 'gato' => __('Gato')] as $value => $label)
                                    <label class="flex items-center gap-2 text-sm">
                                        <input
                                            type="radio"
                                            name="especie"
                                            value="{{ $value }}"
                                            class="accent-[#1f5c47]"
                                            @checked($filtros['especie'] === $value)
                                            onchange="this.form.submit()"
                                        >
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h2 class="mb-3 text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Tamaño') }}</h2>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach (['pequeno' => __('Pequeño'), 'mediano' => __('Mediano'), 'grande' => __('Grande'), 'gigante' => __('Gigante')] as $value => $label)
                                    <label class="relative">
                                        <input
                                            type="checkbox"
                                            name="tamano[]"
                                            value="{{ $value }}"
                                            class="peer sr-only"
                                            @checked(in_array($value, $filtros['tamano'], true))
                                            onchange="this.form.submit()"
                                        >
                                        <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h2 class="mb-3 text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Sexo') }}</h2>
                            <div class="space-y-2">
                                @foreach (['' => __('Todos'), 'macho' => __('Macho'), 'hembra' => __('Hembra')] as $value => $label)
                                    <label class="flex items-center gap-2 text-sm">
                                        <input
                                            type="radio"
                                            name="sexo"
                                            value="{{ $value }}"
                                            class="accent-[#1f5c47]"
                                            @checked($filtros['sexo'] === $value)
                                            onchange="this.form.submit()"
                                        >
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h2 class="mb-3 text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Ciudad') }}</h2>
                            <select
                                name="ciudad"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900"
                                onchange="this.form.submit()"
                            >
                                <option value="" @selected($filtros['ciudad'] === '')>{{ __('Todas') }}</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad }}" @selected($filtros['ciudad'] === $ciudad)>{{ $ciudad }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($caracteristicas->isNotEmpty())
                            <div>
                                <h2 class="mb-3 text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Características') }}</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($caracteristicas as $caracteristica)
                                        <label class="relative">
                                            <input
                                                type="checkbox"
                                                name="caracteristicas[]"
                                                value="{{ $caracteristica->id_caracteristica }}"
                                                class="peer sr-only"
                                                @checked(in_array($caracteristica->id_caracteristica, $filtros['caracteristicas'], true))
                                                onchange="this.form.submit()"
                                            >
                                            <span class="block cursor-pointer rounded-full border border-neutral-300 px-3 py-1.5 text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">
                                                {{ $caracteristica->nombre }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($filtros['especie'] || $filtros['sexo'] || $filtros['ciudad'] || $filtros['tamano'] || $filtros['caracteristicas'])
                            <a href="{{ route('mascotas') }}" class="inline-block text-sm text-[#1f5c47] underline" wire:navigate>
                                {{ __('Limpiar filtros') }}
                            </a>
                        @endif
                    </aside>

                    <div class="lg:col-span-3">
                        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-sm text-neutral-600">
                                <strong class="text-neutral-900">{{ $mascotas->total() }}</strong>
                                {{ __('animales encontrados') }}
                            </p>

                            <select
                                name="orden"
                                class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900"
                                onchange="this.form.submit()"
                            >
                                <option value="recientes" @selected($filtros['orden'] === 'recientes')>{{ __('Más recientes') }}</option>
                                <option value="nombre" @selected($filtros['orden'] === 'nombre')>{{ __('Nombre (A-Z)') }}</option>
                                <option value="edad_asc" @selected($filtros['orden'] === 'edad_asc')>{{ __('Edad (menor a mayor)') }}</option>
                                <option value="edad_desc" @selected($filtros['orden'] === 'edad_desc')>{{ __('Edad (mayor a menor)') }}</option>
                            </select>
                        </div>

                        @if ($mascotas->isEmpty())
                            <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
                                {{ __('No encontramos animales con esos filtros. Probá ajustando la búsqueda.') }}
                            </div>
                        @else
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($mascotas as $mascota)
                                    <a
                                        href="{{ route('mascotas.show', $mascota) }}"
                                        wire:navigate
                                        class="block overflow-hidden rounded-xl border border-neutral-200 bg-white transition hover:border-[#1f5c47]/40 hover:shadow-sm"
                                    >
                                        <div class="relative aspect-square">
                                            <img
                                                src="{{ $mascota->fotoPrincipal?->url ?? $mascota->fotos->first()?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=500&q=80' }}"
                                                alt="{{ $mascota->nombre }}"
                                                class="h-full w-full object-cover"
                                            >
                                            <span class="absolute top-3 left-3 rounded-full bg-[#1f5c47] px-3 py-1 text-xs font-semibold text-white">
                                                {{ __('Disponible') }}
                                            </span>
                                            @if ($mascota->esterilizado)
                                                <span class="absolute top-3 right-3 rounded-full bg-white px-3 py-1 text-xs font-semibold text-neutral-800 shadow">
                                                    {{ __('Esterilizado') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <div class="mb-1 flex items-start justify-between gap-2">
                                                <h3 class="font-semibold">{{ $mascota->nombre }}</h3>
                                                <span class="text-xs text-neutral-500">
                                                    {{ $mascota->sexo === 'macho' ? __('Macho') : __('Hembra') }} · {{ $mascota->tamanoTexto() }}
                                                </span>
                                            </div>
                                            <p class="mb-3 text-sm text-neutral-500">
                                                {{ ucfirst($mascota->especie) }}{{ $mascota->raza?->nombre_raza ? ' '.$mascota->raza->nombre_raza : '' }}
                                                @if ($mascota->edadTexto())
                                                    · {{ $mascota->edadTexto() }}
                                                @endif
                                                <br>
                                                {{ $mascota->sede?->ciudad }}{{ $mascota->sede && $mascota->fundacion ? ' · ' : '' }}{{ $mascota->fundacion?->nombre }}
                                            </p>
                                            @if ($mascota->caracteristicas->isNotEmpty())
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach ($mascota->caracteristicas->take(3) as $tag)
                                                        <span class="rounded-full bg-[#dcece4] px-2.5 py-1 text-xs text-[#234a3a]">{{ $tag->nombre }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-8">
                                {{ $mascotas->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        @fluxScripts
    </body>
</html>
