<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap">
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full flex-col justify-end overflow-hidden p-10 text-white lg:flex">
                <div
                    class="absolute inset-0 bg-cover bg-center"
                    style="background-image: linear-gradient(to top, rgba(9,20,16,.78) 0%, rgba(9,20,16,.22) 50%, rgba(9,20,16,.03) 100%), url('https://images.unsplash.com/photo-1560807707-8cc77767d783?auto=format&fit=crop&w=1200&q=80');"
                ></div>

                <a href="{{ route('home') }}" class="relative z-20 flex items-center gap-2 text-lg font-medium" wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15 backdrop-blur-sm">
                        <img src="{{ asset('footprint_huella_logo.svg') }}" alt="" class="h-5 w-5">
                    </span>
                    <span style="font-family: 'Playfair Display', serif;">{{ config('app.name', 'PeliHuellas') }}</span>
                </a>

                <div class="relative z-20 mt-auto max-w-md rounded-3xl border border-white/10 bg-black/15 p-6 backdrop-blur-md">
                    <h2 class="mb-3 text-3xl leading-tight font-bold" style="font-family: 'Playfair Display', serif;">
                        Cada animal merece un hogar. Cada hogar, el animal correcto.
                    </h2>
                    <p class="mb-8 text-white/85">
                        Fundaciones y veterinarias verificadas. Adopciones con seguridad y transparencia.
                    </p>

                    <div class="flex gap-8">
                        <div>
                            <strong class="block text-2xl font-bold" style="font-family: 'Playfair Display', serif;">3.841</strong>
                            <span class="text-sm text-white/75">Animales</span>
                        </div>
                        <div>
                            <strong class="block text-2xl font-bold" style="font-family: 'Playfair Display', serif;">127</strong>
                            <span class="text-sm text-white/75">Fundaciones</span>
                        </div>
                        <div>
                            <strong class="block text-2xl font-bold" style="font-family: 'Playfair Display', serif;">89</strong>
                            <span class="text-sm text-white/75">Veterinarias</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'PeliHuellas') }}</span>
                    </a>
                    {{ $slot }}

                    <a href="{{ route('home') }}" class="block text-center text-sm text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200" wire:navigate>
                        &larr; Volver al catálogo
                    </a>
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
