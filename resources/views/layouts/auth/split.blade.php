<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="grid min-h-dvh lg:grid-cols-[45fr_55fr]">
            <div class="relative hidden h-dvh flex-col overflow-hidden p-10 text-white lg:flex">
                <img
                    src="{{ asset('images/registrar.jpg') }}"
                    alt="Perro abrazando a un gato en el campo"
                    loading="eager"
                    class="absolute inset-0 h-full w-full object-cover object-[center_40%]"
                >
                <div class="absolute inset-x-0 top-0 h-[35%] bg-linear-to-b from-black/25 via-transparent to-transparent"></div>

                <x-app-logo href="{{ route('home') }}" size="xl" light class="relative z-20" wire:navigate />
            </div>
            <div class="flex min-h-dvh w-full items-center justify-center px-6 py-12 md:px-12 lg:px-16">
                <div class="mx-auto flex w-full max-w-[440px] flex-col justify-center gap-6 lg:max-w-[520px]">
                    <x-app-logo href="{{ route('home') }}" size="sm" class="z-20 justify-center lg:hidden" wire:navigate />
                    {{ $slot }}

                    <a href="{{ route('home') }}" class="block text-center text-[15px] text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200" wire:navigate>
                        &larr; Volver al inicio
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
