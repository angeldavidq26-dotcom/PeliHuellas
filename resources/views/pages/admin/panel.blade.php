<x-admin-shell active="panel">
    <div class="mb-8">
        <h1 class="pf-serif text-3xl font-bold">{{ __('Panel de administración') }}</h1>
        <p class="text-neutral-500">{{ __('Resumen general de la plataforma PeliHuellas') }}</p>
    </div>

    @if ($solicitudesUrgentes > 0)
        <div class="mb-6 flex flex-wrap items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            <flux:icon name="exclamation-triangle" variant="micro" class="size-5 shrink-0 text-amber-600" />
            <div class="flex-1">
                <p class="font-semibold">
                    {{ trans_choice('Hay :count solicitud esperando respuesta hace más de 5 días|Hay :count solicitudes esperando respuesta hace más de 5 días', $solicitudesUrgentes, ['count' => $solicitudesUrgentes]) }}
                </p>
                <p class="text-amber-800">{{ __('Las fundaciones involucradas no han respondido. Podés intervenir directamente.') }}</p>
            </div>
            <flux:button :href="route('admin.solicitudes')" variant="filled" size="sm" wire:navigate>
                {{ __('Ver solicitudes') }}
            </flux:button>
        </div>
    @endif

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
            <p class="pf-serif text-2xl font-bold text-amber-700">{{ $stats['fundaciones_pendientes'] }}</p>
            <p class="text-sm text-amber-700">{{ __('Fundaciones pendientes de verificar') }}</p>
        </div>
        <div class="rounded-xl border border-[#bfe0d0] bg-[#f2f8f5] p-5">
            <p class="pf-serif text-2xl font-bold text-[#1f5c47]">{{ $stats['fundaciones_activas'] }}</p>
            <p class="text-sm text-[#1f5c47]">{{ __('Fundaciones activas') }}</p>
        </div>
        <div class="rounded-xl border border-neutral-200 bg-white p-5">
            <p class="pf-serif text-2xl font-bold">{{ $stats['animales_publicados'] }}</p>
            <p class="text-sm text-neutral-500">{{ __('Animales publicados') }}</p>
        </div>
        <div class="rounded-xl border border-red-200 bg-red-50 p-5">
            <p class="pf-serif text-2xl font-bold text-red-700">{{ $stats['solicitudes_sin_resolver'] }}</p>
            <p class="text-sm text-red-700">{{ __('Solicitudes sin resolver') }}</p>
        </div>
        <div class="rounded-xl border border-[#bfe0d0] bg-[#f2f8f5] p-5">
            <p class="pf-serif text-2xl font-bold text-[#1f5c47]">{{ $stats['adopciones_del_mes'] }}</p>
            <p class="text-sm text-[#1f5c47]">{{ __('Adopciones completadas este mes') }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-neutral-200 bg-white">
        <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-4">
            <h2 class="pf-serif text-lg font-bold">{{ __('Verificaciones más antiguas sin revisar') }}</h2>
            <a href="{{ route('admin.verificaciones') }}" class="text-sm font-medium text-[#b3402e]" wire:navigate>{{ __('Ver todas →') }}</a>
        </div>

        @if ($verificacionesAntiguas->isEmpty())
            <p class="p-6 text-center text-sm text-neutral-500">{{ __('No hay fundaciones pendientes de verificar.') }}</p>
        @else
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Fundación') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('NIT') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Fecha solicitud') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Días esperando') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($verificacionesAntiguas as $fundacion)
                        @php $dias = $fundacion->fecha_registro->diffInDays(now()); @endphp
                        <tr>
                            <td class="px-5 py-3 font-medium text-[#1f5c47]">{{ $fundacion->nombre }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->nit }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $fundacion->fecha_registro->format('Y-m-d') }}</td>
                            <td class="px-5 py-3">
                                <span class="{{ $dias >= 5 ? 'font-semibold text-amber-600' : 'text-neutral-600' }}">
                                    {{ trans_choice(':count día|:count días', $dias, ['count' => $dias]) }}
                                    @if ($dias >= 5)
                                        <flux:icon name="exclamation-triangle" variant="micro" class="inline size-3" />
                                    @endif
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <flux:button :href="route('admin.verificaciones', ['fundacion' => $fundacion->id_fundacion])" size="sm" variant="filled" wire:navigate>
                                    {{ __('Revisar') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-admin-shell>
