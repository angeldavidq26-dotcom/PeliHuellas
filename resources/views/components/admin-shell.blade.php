@props(['active'])

@php
    $verificacionesPendientes = \App\Models\Fundacion::query()->where('estado_verificacion', 'pendiente')->count();
    $solicitudesUrgentes = \App\Models\SolicitudAdopcion::query()
        ->where('estado', 'pendiente')
        ->where('fecha_solicitud', '<=', now()->subDays(5))
        ->count();

    $items = [
        ['key' => 'panel', 'label' => __('Panel'), 'icon' => 'home', 'route' => route('admin.panel')],
        ['key' => 'verificaciones', 'label' => __('Verificaciones'), 'icon' => 'shield-check', 'route' => route('admin.verificaciones'), 'badge' => $verificacionesPendientes],
        ['key' => 'solicitudes', 'label' => __('Solicitudes'), 'icon' => 'document-text', 'route' => route('admin.solicitudes'), 'badge' => $solicitudesUrgentes],
        ['key' => 'usuarios', 'label' => __('Usuarios'), 'icon' => 'users', 'route' => route('admin.usuarios')],
        ['key' => 'fundaciones', 'label' => __('Fundaciones'), 'icon' => 'building-office', 'route' => route('admin.fundaciones')],
        ['key' => 'razas', 'label' => __('Razas'), 'icon' => 'tag', 'route' => route('admin.razas')],
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
                <div class="mb-6 flex items-center gap-3 px-2">
                    <a href="{{ route('admin.panel') }}" class="flex min-w-0 items-center gap-3" wire:navigate>
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#fbe3dc] text-[#b3402e]">
                            <flux:icon name="shield-check" variant="micro" class="size-5" />
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-semibold text-neutral-900">{{ __('PeliHuellas') }}</span>
                            <span class="block text-xs font-medium text-[#b3402e]">{{ __('Administrador') }}</span>
                        </span>
                    </a>
                </div>

                <nav class="flex flex-1 flex-col gap-1">
                    @foreach ($items as $item)
                        <a
                            href="{{ $item['route'] }}"
                            wire:navigate
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ $active === $item['key'] ? 'bg-[#fbe3dc] text-[#b3402e]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <flux:icon :name="$item['icon']" variant="micro" class="size-4" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if (! empty($item['badge']))
                                <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-[#b3402e] px-1.5 text-xs font-semibold text-white">
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
