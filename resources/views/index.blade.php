<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-white text-neutral-900 antialiased">
        <x-site-header />

        @include('partials.home.hero')
        @include('partials.home.stats')
        @include('partials.home.catalog')
        @include('partials.home.seguridad')
        @include('partials.home.footer')

        @fluxScripts
    </body>
</html>
