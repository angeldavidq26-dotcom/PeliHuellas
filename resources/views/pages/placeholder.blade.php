<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap">

        <style>
            .pf-serif { font-family: 'Playfair Display', Georgia, serif; }
        </style>
    </head>
    <body class="bg-neutral-50 text-neutral-900 antialiased">
        <x-site-header />

        <div class="mx-auto max-w-3xl px-6 py-24 text-center">
            <p class="mb-2 text-xs font-semibold tracking-widest text-[#1f5c47] uppercase">{{ __('PeliHuellas') }}</p>
            <h1 class="pf-serif mb-4 text-3xl font-bold sm:text-4xl">{{ $titulo }}</h1>
            <p class="mb-8 text-neutral-600">
                {{ __('Estamos construyendo esta sección. Muy pronto vas a poder usarla desde acá.') }}
            </p>
            <flux:button :href="route('home')" variant="primary" wire:navigate>
                {{ __('Volver al inicio') }}
            </flux:button>
        </div>

        @fluxScripts
    </body>
</html>
