<x-fundacion-shell :fundacion="$fundacion" :active="$active">
    <div class="flex h-full flex-col items-center justify-center rounded-xl border border-dashed border-neutral-300 bg-white p-16 text-center">
        <p class="mb-2 text-xs font-semibold tracking-widest text-[#1f5c47] uppercase">{{ __('PeliHuellas') }}</p>
        <h1 class="pf-serif mb-3 text-2xl font-bold">{{ $titulo }}</h1>
        <p class="text-neutral-500">{{ __('Estamos construyendo esta sección. Muy pronto vas a poder usarla desde acá.') }}</p>
    </div>
</x-fundacion-shell>
