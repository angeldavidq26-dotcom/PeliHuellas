<section class="relative h-[650px] overflow-hidden sm:h-[760px] md:h-[840px]">
    <div
        class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('{{ asset('images/UHJpbmNpcGFsTWFzY290YXM=.jpg') }}');"
    ></div>
    <div
        class="absolute inset-0"
        style="background-image: linear-gradient(to right, rgba(27,92,79,0.82) 0%, rgba(27,92,79,0.50) 50%, rgba(27,92,79,0.15) 100%);"
    ></div>

    <div class="relative mx-auto flex h-full max-w-7xl items-center px-6">
        <div class="w-full max-w-[680px] lg:-translate-x-[90px]">
            <p class="mb-4 text-xs font-semibold tracking-widest text-white/90 uppercase">
                {{ __('Conectamos, no ejecutamos') }}
            </p>
            <h1 class="pf-serif mb-6 text-[54px] leading-[1.05] font-bold text-white sm:text-[76px]">
                {{ __('Cada animal merece un hogar. Cada hogar, el animal correcto.') }}
            </h1>
            <p class="mb-9 text-lg text-white/85 sm:text-xl">
                {{ __('Fundaciones y veterinarias verificadas en un solo lugar. Adoptá con seguridad, cuidá con confianza.') }}
            </p>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <a
                    href="{{ route('mascotas') }}"
                    wire:navigate
                    class="inline-flex h-16 items-center justify-center rounded-lg bg-white px-10 text-lg font-semibold text-[#1B5C4F] transition hover:bg-white/90"
                >
                    {{ __('Quiero adoptar') }}
                </a>
                <a
                    href="{{ route('solicitud-fundacion.formulario') }}"
                    wire:navigate
                    class="inline-flex h-16 items-center justify-center rounded-lg bg-[#1B5C4F] px-10 text-lg font-semibold text-white transition hover:bg-[#154A40]"
                >
                    {{ __('Registrar mi fundación') }}
                </a>
            </div>
        </div>
    </div>
</section>
