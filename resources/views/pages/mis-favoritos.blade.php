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

        <div class="mx-auto max-w-4xl px-6 py-10">
            <livewire:mis-favoritos />
        </div>

        @fluxScripts
    </body>
</html>
