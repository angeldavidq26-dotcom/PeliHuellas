<section class="bg-neutral-50">
    <div class="w-full px-8 py-14">
        <div class="mb-6 flex items-end justify-between">
            <div>
                <p class="mb-1 text-xs font-semibold tracking-widest text-[#1B5C4F] uppercase">{{ __('Adopción') }}</p>
                <h2 class="pf-serif text-2xl font-bold sm:text-3xl">{{ __('Animales esperando hogar') }}</h2>
            </div>
            <flux:link :href="route('mascotas')" wire:navigate>{{ __('Ver todos →') }}</flux:link>
        </div>

        @php
            // Datos de ejemplo mientras no exista un catálogo dinámico de mascotas.
            $mascotas = [
                ['nombre' => 'Bruno', 'tamano' => 'Grande', 'edad' => '3 años', 'ciudad' => 'Bogotá', 'fundacion' => 'Huellas Felices', 'tags' => ['Juguetón', 'Sociable', 'Apto apartamento'], 'foto' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=500&q=80'],
                ['nombre' => 'Mia', 'tamano' => 'Pequeña', 'edad' => '2 años', 'ciudad' => 'Medellín', 'fundacion' => 'AnimalesYA', 'tags' => ['Tranquila', 'Con niños'], 'foto' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=500&q=80'],
                ['nombre' => 'Rocky', 'tamano' => 'Mediano', 'edad' => '5 años', 'ciudad' => 'Cali', 'fundacion' => 'Patas Libres', 'tags' => ['Juguetón', 'Energético'], 'foto' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=500&q=80'],
                ['nombre' => 'Nala', 'tamano' => 'Pequeña', 'edad' => '1 año', 'ciudad' => 'Bogotá', 'fundacion' => 'Huellas Felices', 'tags' => ['Sociable', 'Con niños', 'Tranquila'], 'foto' => 'https://images.unsplash.com/photo-1601979031925-424e53b6caaa?auto=format&fit=crop&w=500&q=80'],
                ['nombre' => 'Venus', 'tamano' => 'Mediana', 'edad' => '3 años', 'ciudad' => 'Tolima', 'fundacion' => 'Colitas y Bigotes', 'tags' => ['Cariñosa', 'Juguetona', 'Tranquila'], 'foto' => 'https://images.unsplash.com/photo-1561948955-570b270e7c36?q=80&w=601&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'],
                ['nombre' => 'Thor', 'tamano' => 'Grande', 'edad' => '4 años', 'ciudad' => 'Chia', 'fundacion' => 'Colitas y Bigotes', 'tags' => ['Jugueton', 'Sociable', 'Tranquilo'], 'foto' => 'https://images.unsplash.com/photo-1690052346621-e541ac1efe9e?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'],
            ];
        @endphp

        <div
            x-data="petsCarousel()"
            x-init="init()"
            @mouseenter="paused = true"
            @mouseleave="paused = false"
            class="relative overflow-hidden"
        >
            <div
                x-ref="track"
                class="pets-track flex gap-6 pb-4"
                :class="{ 'pets-track-paused': paused }"
            >
                @foreach (array_merge($mascotas, $mascotas, $mascotas, $mascotas, $mascotas) as $mascota)
                    <div class="pet-card w-56 shrink-0">
                        <div class="group h-full overflow-hidden rounded-xl border border-neutral-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:border-neutral-300 hover:shadow-lg">
                            <div class="relative aspect-square overflow-hidden">
                                <img src="{{ $mascota['foto'] }}" alt="{{ $mascota['nombre'] }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <span class="absolute top-3 left-3 rounded-full bg-[#1B5C4F] px-3 py-1 text-xs font-semibold text-white">
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    .pets-track {
        animation: scroll-pets-ltr 28s linear infinite;
        will-change: transform;
    }
    .pets-track-paused {
        animation-play-state: paused;
    }
    @keyframes scroll-pets-ltr {
        from { transform: translateX(-20%); }
        to { transform: translateX(0%); }
    }
    .pet-card {
        transition: opacity 0.15s linear, filter 0.15s linear;
    }
</style>

<script>
    function petsCarousel() {
        return {
            paused: false,
            raf: null,
            init() {
                const loop = () => {
                    this.updateCards();
                    this.raf = requestAnimationFrame(loop);
                };
                this.raf = requestAnimationFrame(loop);
                this.$el.addEventListener('alpine:destroyed', () => cancelAnimationFrame(this.raf));
            },
            updateCards() {
                const containerRect = this.$el.getBoundingClientRect();
                const cards = this.$refs.track.querySelectorAll('.pet-card');
                if (!cards.length) return;

                // Zona de difuminado = 1.5 tarjetas (ancho de tarjeta + espacio entre ellas)
                const cardWidth = cards[0].getBoundingClientRect().width;
                const gap = 24; // debe coincidir con gap-6
                const fadeZone = (cardWidth + gap) * 1.5;

                cards.forEach((card) => {
                    const rect = card.getBoundingClientRect();
                    const cardCenter = rect.left + rect.width / 2;

                    const distanceFromLeftEdge = cardCenter - containerRect.left;
                    const distanceFromRightEdge = containerRect.right - cardCenter;
                    const distanceFromEdge = Math.min(distanceFromLeftEdge, distanceFromRightEdge);

                    // ratio = 0 fuera de la zona de difuminado, 1 justo en el borde
                    const ratio = Math.min(Math.max(1 - distanceFromEdge / fadeZone, 0), 1);

                    card.style.opacity = 1 - ratio * 0.45;
                    card.style.filter = `grayscale(${ratio * 0.75})`;
                    card.style.transform = `translateY(${ratio * 10}px)`;
                });
            },
        };
    }
</script>
