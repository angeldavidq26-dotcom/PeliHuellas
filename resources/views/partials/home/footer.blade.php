<footer class="border-t border-neutral-200 bg-white">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 py-14 sm:grid-cols-2">
        <div>
            <x-app-logo href="{{ route('home') }}" size="sm" class="mb-2" wire:navigate />
            <p class="max-w-sm text-sm text-neutral-500">{{ __('Conectamos fundaciones y veterinarias con familias responsables.') }}</p>
        </div>

        <div class="sm:justify-self-end">
            <p class="mb-3 text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Soporte') }}</p>
            <ul class="space-y-2 text-sm text-neutral-600">
                <li><flux:link href="#">{{ __('Preguntas frecuentes') }}</flux:link></li>
                <li><flux:link href="mailto:hola@pelihuellas.co">{{ __('Contacto') }}</flux:link></li>
                <li><flux:link href="#">{{ __('Términos') }}</flux:link></li>
                <li><flux:link href="#">{{ __('Privacidad') }}</flux:link></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-neutral-200">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-6 text-sm text-neutral-500 sm:flex-row sm:items-center sm:justify-between">
            <p>{{ __('© :year PeliHuellas. Todos los derechos reservados.', ['year' => date('Y')]) }}</p>
            <a href="mailto:hola@pelihuellas.co" class="hover:text-neutral-700">hola@pelihuellas.co</a>
        </div>
    </div>
</footer>
