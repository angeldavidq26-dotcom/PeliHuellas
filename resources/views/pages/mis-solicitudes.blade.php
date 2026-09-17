<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-neutral-50 text-neutral-900 antialiased">
        <x-site-header />

        <div class="mx-auto max-w-4xl px-6 py-10">
            <livewire:mis-solicitudes />
        </div>

        @fluxScripts
    </body>
</html>
