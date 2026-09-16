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
    <body class="bg-white text-neutral-900 antialiased">
        @include('partials.home.header')
        @include('partials.home.hero')
        @include('partials.home.stats')
        @include('partials.home.catalog')
        @include('partials.home.seguridad')
        @include('partials.home.footer')

        @fluxScripts
    </body>
</html>
