<x-fundacion-shell :fundacion="$fundacion" active="sedes">
    <div class="mb-8">
        <h1 class="pf-serif text-3xl font-bold">{{ __('Sedes') }}</h1>
        <p class="text-neutral-500">{{ __('Las sedes desde donde :nombre publica animales.', ['nombre' => $fundacion->nombre]) }}</p>
    </div>

    @if ($sedes->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('Todavía no registraste ninguna sede.') }}
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($sedes as $sede)
                <div class="rounded-xl border border-neutral-200 bg-white p-5">
                    <div class="mb-2 flex items-start justify-between gap-2">
                        <h3 class="font-semibold">{{ $sede->nombre }}</h3>
                        @if ($sede->es_principal)
                            <span class="rounded-full bg-[#dcece4] px-2.5 py-1 text-xs font-semibold text-[#234a3a]">{{ __('Principal') }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-neutral-500">{{ $sede->direccion }}</p>
                    <p class="text-sm text-neutral-500">{{ $sede->ciudad }}</p>
                    @if ($sede->telefono)
                        <p class="mt-2 text-sm text-neutral-500">{{ $sede->telefono }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-fundacion-shell>
