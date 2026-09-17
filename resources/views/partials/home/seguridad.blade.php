<section class="bg-[#f4f5f4]">
    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-6 py-16 lg:grid-cols-2 lg:gap-16">
        <div>
            <p class="mb-3 text-xs font-semibold tracking-widest text-[#1B5C4F] uppercase">{{ __('Seguridad') }}</p>
            <h2 class="pf-serif mb-4 text-3xl leading-tight font-bold sm:text-4xl">
                {{ __('Todas las organizaciones pasan por verificación documental') }}
            </h2>
            <p class="mb-6 max-w-xl text-neutral-600">
                {{ __('Antes de publicar un animal o un servicio, cada fundación y veterinaria debe presentar su certificado de existencia y el documento de su representante legal. Revisamos todo manualmente. No hay atajos.') }}
            </p>

            <ul class="space-y-3">
                @foreach ([
                    __('Certificado de existencia y representación legal'),
                    __('Verificación manual por el equipo de PeliHuellas'),
                    __('Sello visible en cada perfil verificado'),
                ] as $punto)
                    <li class="flex items-center gap-3 text-sm text-neutral-700">
                        <svg class="h-5 w-5 shrink-0 text-[#1B5C4F]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.415l-7.09 7.09a1 1 0 01-1.415 0L4.296 9.89a1 1 0 111.415-1.414l3.09 3.09 6.383-6.383a1 1 0 011.42.107z" clip-rule="evenodd" />
                        </svg>
                        {{ $punto }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="overflow-hidden rounded-2xl">
            <img
                src="https://images.unsplash.com/photo-1548247416-ec66f4900b2e?auto=format&fit=crop&w=900&q=80"
                alt="{{ __('Gato verificado en un perfil de PeliHuellas') }}"
                class="h-full w-full object-cover"
                loading="lazy"
            >
        </div>
    </div>
</section>
