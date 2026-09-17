<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-[#F3F6F4] text-neutral-900 antialiased">
        <x-site-header :guest-actions="false" />

        <div class="mx-auto max-w-5xl px-6 py-10">
            <livewire:revision-fundaciones />
        </div>

        @fluxScripts
    </body>
</html>
