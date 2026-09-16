<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap">

        <style>
            .pf-serif { font-family: 'Playfair Display', Georgia, serif; }
        </style>
    </head>
    <body class="bg-white text-neutral-900 antialiased">
<<<<<<< HEAD
        <x-site-header />

        <section class="relative overflow-hidden">
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: linear-gradient(to top, rgba(10,25,18,.55) 0%, rgba(10,25,18,.35) 45%, rgba(10,25,18,.1) 100%), url('https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1600&q=80');"
            ></div>

            <div class="relative mx-auto max-w-3xl px-6 py-24 sm:py-32">
                <p class="mb-3 text-xs font-semibold tracking-widest text-white/90 uppercase">
                    {{ __('Conectamos, no ejecutamos') }}
                </p>
                <h1 class="pf-serif mb-4 text-4xl leading-tight font-bold text-white sm:text-5xl">
                    {{ __('Cada animal merece un hogar. Cada hogar, el animal correcto.') }}
                </h1>
                <p class="mb-8 max-w-xl text-white/85">
                    {{ __('Fundaciones y veterinarias verificadas en un solo lugar. Adoptá con seguridad, cuidá con confianza.') }}
                </p>

                <div class="flex flex-wrap gap-3">
                    <flux:button :href="route('mascotas')" wire:navigate>{{ __('Quiero adoptar') }}</flux:button>
                    <flux:button :href="route('register')" variant="ghost" class="bg-transparent! text-white! ring-1 ring-white/70!" wire:navigate>
                        {{ __('Cuidar a mi mascota') }}
                    </flux:button>
                    <flux:button :href="route('register')" variant="primary" wire:navigate>
                        {{ __('Registrar mi fundación') }}
                    </flux:button>
                </div>
            </div>
        </section>

        <section class="bg-[#1f5c47]">
            <div class="mx-auto grid max-w-7xl grid-cols-3 gap-4 px-6 py-10 text-center text-white">
                <div>
                    <strong class="pf-serif block text-3xl font-bold">3.841</strong>
                    <span class="text-sm text-white/80">{{ __('Animales publicados') }}</span>
                </div>
                <div>
                    <strong class="pf-serif block text-3xl font-bold">127</strong>
                    <span class="text-sm text-white/80">{{ __('Fundaciones verificadas') }}</span>
                </div>
                <div>
                    <strong class="pf-serif block text-3xl font-bold">89</strong>
                    <span class="text-sm text-white/80">{{ __('Veterinarias aliadas') }}</span>
                </div>
            </div>
        </section>

        <section class="bg-neutral-50">
            <div class="mx-auto max-w-7xl px-6 py-14">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <p class="mb-1 text-xs font-semibold tracking-widest text-[#1f5c47] uppercase">{{ __('Adopción') }}</p>
                        <h2 class="pf-serif text-2xl font-bold sm:text-3xl">{{ __('Animales esperando hogar') }}</h2>
                    </div>
                    <flux:link :href="route('mascotas')" wire:navigate>{{ __('Ver todos →') }}</flux:link>
                </div>

                {{-- Datos de ejemplo mientras no exista un catálogo dinámico de mascotas. --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['nombre' => 'Bruno', 'tamano' => 'Grande', 'edad' => '3 años', 'ciudad' => 'Bogotá', 'fundacion' => 'Huellas Felices', 'tags' => ['Juguetón', 'Sociable', 'Apto apartamento'], 'foto' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=500&q=80'],
                        ['nombre' => 'Mia', 'tamano' => 'Pequeña', 'edad' => '2 años', 'ciudad' => 'Medellín', 'fundacion' => 'AnimalesYA', 'tags' => ['Tranquila', 'Con niños'], 'foto' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=500&q=80'],
                        ['nombre' => 'Rocky', 'tamano' => 'Mediano', 'edad' => '5 años', 'ciudad' => 'Cali', 'fundacion' => 'Patas Libres', 'tags' => ['Juguetón', 'Energético'], 'foto' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=500&q=80'],
                        ['nombre' => 'Nala', 'tamano' => 'Grande', 'edad' => '1 año', 'ciudad' => 'Bogotá', 'fundacion' => 'Huellas Felices', 'tags' => ['Sociable', 'Con niños', 'Tranquila'], 'foto' => 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&w=500&q=80'],
                    ] as $mascota)
                        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
                            <div class="relative aspect-square">
                                <img src="{{ $mascota['foto'] }}" alt="{{ $mascota['nombre'] }}" class="h-full w-full object-cover">
                                <span class="absolute top-3 left-3 rounded-full bg-[#1f5c47] px-3 py-1 text-xs font-semibold text-white">
                                    {{ __('Disponible') }}
                                </span>
                            </div>
                            <div class="p-4">
                                <div class="mb-1 flex items-start justify-between gap-2">
                                    <h3 class="font-semibold">{{ $mascota['nombre'] }}</h3>
                                    <span class="text-xs text-neutral-500">{{ $mascota['tamano'] }}</span>
                                </div>
                                <p class="mb-3 text-sm text-neutral-500">
                                    {{ $mascota['edad'] }} · {{ $mascota['ciudad'] }}<br>
                                    {{ $mascota['fundacion'] }}
                                </p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($mascota['tags'] as $tag)
                                        <span class="rounded-full bg-[#dcece4] px-2.5 py-1 text-xs text-[#234a3a]">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
=======
        @include('partials.home.header')
        @include('partials.home.hero')
        @include('partials.home.stats')
        @include('partials.home.catalog')
        @include('partials.home.seguridad')
        @include('partials.home.footer')
>>>>>>> 559177032a473abdf8ad2e0875caefbf3bf07480

        @fluxScripts
    </body>
</html>
