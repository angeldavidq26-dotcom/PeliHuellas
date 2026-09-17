<section class="relative overflow-hidden">
    <div
        class="absolute inset-0 bg-cover bg-center"
        style="background-image: linear-gradient(100deg, rgba(9,20,16,.72) 0%, rgba(9,20,16,.42) 38%, rgba(9,20,16,.08) 65%), linear-gradient(to top, rgba(9,20,16,.5) 0%, rgba(9,20,16,0) 40%), url('https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1600&q=80');"
    ></div>

    <div class="relative mx-auto max-w-3xl px-6 py-24 sm:py-32">
        <div class="max-w-xl rounded-3xl border border-white/10 bg-black/20 p-8 backdrop-blur-md sm:p-10">
            <p class="mb-3 text-xs font-semibold tracking-widest text-white/90 uppercase">
                {{ __('Conectamos, no ejecutamos') }}
            </p>
            <h1 class="pf-serif mb-4 text-4xl leading-tight font-bold text-white sm:text-5xl">
                {{ __('Cada animal merece un hogar. Cada hogar, el animal correcto.') }}
            </h1>
            <p class="mb-8 text-white/85">
                {{ __('Fundaciones y veterinarias verificadas en un solo lugar. Adoptá con seguridad y transparencia.') }}
            </p>

            <div class="flex flex-wrap gap-3">
                <flux:button :href="route('mascotas')" wire:navigate>{{ __('Quiero adoptar') }}</flux:button>
                <flux:button :href="route('register')" variant="ghost" class="bg-transparent! text-white! ring-1 ring-white/70!" wire:navigate>
                    {{ __('Cuidar a mi mascota') }}
                </flux:button>
                <flux:button :href="route('solicitud-fundacion.formulario')" variant="primary" wire:navigate>
                    {{ __('Registrar mi fundación') }}
                </flux:button>
            </div>
        </div>
    </div>
</section>
