<x-admin-shell active="fundaciones">
    <div class="mb-8">
        <h1 class="pf-serif text-3xl font-bold">{{ __('Fundaciones') }}</h1>
        <p class="text-neutral-500">{{ __('Lista completa de fundaciones registradas en la plataforma') }}</p>
    </div>

    <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
        @if ($fundaciones->isEmpty())
            <p class="p-6 text-center text-sm text-neutral-500">{{ __('Todavía no hay fundaciones registradas.') }}</p>
        @else
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Nombre') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('NIT') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Ciudad') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Animales') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Sedes') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Registro') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Estado') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($fundaciones as $fundacion)
                        <tr>
                            <td class="px-5 py-3 font-medium text-[#1f5c47]">{{ $fundacion->nombre }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->nit }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->sedes->first()?->ciudad ?? '—' }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->mascotas_count }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->sedes_count }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->fecha_registro->format('Y-m-d') }}</td>
                            <td class="px-5 py-3">
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-[#dcece4] text-[#234a3a]' => $fundacion->estado_verificacion === 'aprobada',
                                    'bg-amber-100 text-amber-700' => $fundacion->estado_verificacion === 'pendiente',
                                    'bg-red-100 text-red-700' => $fundacion->estado_verificacion === 'rechazada',
                                ])>
                                    {{ match ($fundacion->estado_verificacion) {
                                        'aprobada' => __('Verificada'),
                                        'pendiente' => __('Pendiente'),
                                        default => __('Rechazada'),
                                    } }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-admin-shell>
