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
    <body class="bg-[#F3F6F4] text-neutral-900 antialiased">
        <x-site-header :guest-actions="false" />

        <div class="mx-auto max-w-5xl px-6 py-10">
            <livewire:revision-fundaciones />
        </div>

        @fluxScripts
    </body>
</html>
