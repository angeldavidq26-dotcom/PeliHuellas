<header class="border-b border-neutral-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1f5c47]">
                <img src="{{ asset('footprint_huella_logo.svg') }}" alt="" class="h-4 w-4">
            </span>
            <span class="pf-serif text-lg font-bold text-[#163f31]">{{ config('app.name', 'PeliHuellas') }}</span>
        </a>

        <div class="flex items-center gap-4">
            @auth
                <flux:button :href="route('dashboard')" variant="primary" wire:navigate>
                    {{ __('Ir a mi panel') }}
                </flux:button>
            @else
                <flux:link :href="route('login')" wire:navigate>{{ __('Ingresar') }}</flux:link>
                <flux:button :href="route('register')" variant="primary" wire:navigate>
                    {{ __('Crear cuenta') }}
                </flux:button>
            @endauth
        </div>
    </div>
</header>
