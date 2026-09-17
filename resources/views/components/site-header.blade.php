@props(['guestActions' => true])

<header {{ $attributes->merge(['class' => 'border-b border-neutral-200 bg-white']) }}>
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1f5c47]">
                <img src="{{ asset('footprint_huella_logo.svg') }}" alt="" class="h-4 w-4">
            </span>
            <span class="pf-serif text-lg font-bold text-[#163f31]">PeliHuellas</span>
        </a>

        @auth
            <nav class="hidden items-center gap-6 text-sm font-medium text-neutral-700 md:flex">
                <flux:link :href="route('mascotas')" :class="request()->routeIs('mascotas') ? 'text-[#1f5c47]' : ''" wire:navigate>
                    {{ __('Adoptar') }}
                </flux:link>
                <flux:link :href="route('servicios')" :class="request()->routeIs('servicios') ? 'text-[#1f5c47]' : ''" wire:navigate>
                    {{ __('Servicios') }}
                </flux:link>
                <flux:link :href="route('solicitudes')" :class="request()->routeIs('solicitudes') ? 'text-[#1f5c47]' : ''" wire:navigate>
                    {{ __('Solicitudes') }}
                </flux:link>
                <flux:link :href="route('favoritos')" :class="request()->routeIs('favoritos') ? 'text-[#1f5c47]' : ''" wire:navigate>
                    {{ __('Favoritos') }}
                </flux:link>
                <flux:link :href="route('profile.edit')" :class="request()->routeIs('profile.edit') ? 'text-[#1f5c47]' : ''" wire:navigate>
                    {{ __('Mi perfil') }}
                </flux:link>
                <flux:link :href="route('citas')" :class="request()->routeIs('citas') ? 'text-[#1f5c47]' : ''" wire:navigate>
                    {{ __('Mis citas') }}
                </flux:link>
            </nav>

            <livewire:notificaciones-menu />

            <flux:button :href="route('dashboard')" variant="primary" wire:navigate>
                {{ __('Ir a mi panel') }}
            </flux:button>
        @elseif ($guestActions)
            <nav class="flex items-center gap-4">
                <flux:link :href="route('login')" wire:navigate>{{ __('Ingresar') }}</flux:link>
                <flux:button :href="route('register')" variant="primary" wire:navigate>
                    {{ __('Crear cuenta') }}
                </flux:button>
            </nav>
        @endif
    </div>
</header>
