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
                ['nombre' => 'Nala', 'tamano' => 'Grande', 'edad' => '1 año', 'ciudad' => 'Bogotá', 'fundacion' => 'Huellas Felices', 'tags' => ['Sociable', 'Con niños', 'Tranquila'], 'foto' => 'https://images.unsplash.com/photo-1601979031925-424e53b6caaa?auto=format&fit=crop&w=500&q=80'],
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
