@props(['fundacion', 'active'])

@php
    $enEspera = $fundacion->mascotas()->where('estado', 'espera')->count();
    $solicitudesSinRevisar = \Illuminate\Support\Facades\DB::table('solicitud_adopcion')
        ->join('mascota', 'mascota.id_mascota', '=', 'solicitud_adopcion.id_mascota')
        ->where('mascota.id_fundacion', $fundacion->id_fundacion)
        ->where('solicitud_adopcion.estado', 'pendiente')
        ->count();

    $items = [
        ['key' => 'panel', 'label' => __('Panel'), 'icon' => 'home', 'route' => route('fundacion.panel')],
        ['key' => 'mis-animales', 'label' => __('Mis animales'), 'icon' => 'heart', 'route' => route('fundacion.mis-animales'), 'badge' => $enEspera],
        ['key' => 'solicitudes', 'label' => __('Solicitudes'), 'icon' => 'document-text', 'route' => route('fundacion.solicitudes'), 'badge' => $solicitudesSinRevisar],
        ['key' => 'adopciones', 'label' => __('Adopciones'), 'icon' => 'calendar-days', 'route' => route('fundacion.adopciones')],
        ['key' => 'publicar', 'label' => __('Publicar animal'), 'icon' => 'plus', 'route' => route('fundacion.publicar')],
        ['key' => 'verificacion', 'label' => __('Verificación'), 'icon' => 'check-circle', 'route' => route('fundacion.verificacion')],
        ['key' => 'sedes', 'label' => __('Sedes'), 'icon' => 'map-pin', 'route' => route('fundacion.sedes')],
        ['key' => 'auditoria', 'label' => __('Auditoría'), 'icon' => 'shield-check', 'route' => route('fundacion.auditoria')],
        ['key' => 'configuracion', 'label' => __('Configuración'), 'icon' => 'cog', 'route' => route('fundacion.configuracion')],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="antialiased">
        <div class="flex min-h-screen bg-[#F3F6F4] text-neutral-900">
            <aside class="flex w-64 shrink-0 flex-col border-r border-neutral-200 bg-white px-4 py-6">
                <div class="mb-6 flex items-center justify-between px-2">
                    <a href="{{ route('fundacion.panel') }}" class="flex min-w-0 items-center gap-3" wire:navigate>
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#dcece4] text-[#1f5c47]">
                            <flux:icon name="home" variant="micro" class="size-5" />
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-semibold text-neutral-900">{{ $fundacion->nombre }}</span>
                            <span class="block text-xs text-neutral-500">{{ __('Fundación') }}</span>
                        </span>
                    </a>

                    <div class="shrink-0">
                        <livewire:notificaciones-menu />
                    </div>
                </div>

                <nav class="flex flex-1 flex-col gap-1">
                    @foreach ($items as $item)
                        <a
                            href="{{ $item['route'] }}"
                            wire:navigate
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ $active === $item['key'] ? 'bg-[#dcece4] text-[#1f5c47]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <flux:icon :name="$item['icon']" variant="micro" class="size-4" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if (! empty($item['badge']))
                                <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-[#1f5c47] px-1.5 text-xs font-semibold text-white">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-sm text-neutral-500 hover:text-neutral-700">
                        <flux:icon name="arrow-left" variant="micro" class="size-4" />
                        {{ __('Salir al sitio') }}
                    </button>
                </form>
            </aside>

            <main class="flex-1 overflow-x-hidden px-10 py-8">
                {{ $slot }}
            </main>
        </div>

        @fluxScripts
    </body>
</html>
