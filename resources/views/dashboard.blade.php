<x-layouts::app :title="__('Dashboard')">
    <livewire:pages::teams.pending-invitations-modal />

    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <p class="mb-1 text-xs font-semibold tracking-widest text-accent uppercase">{{ __('Tu panel') }}</p>
            <h1 class="pf-serif text-2xl font-bold text-zinc-900 sm:text-3xl dark:text-white">
                {{ __('Hola, :name', ['name' => explode(' ', auth()->user()->name)[0]]) }}
            </h1>
        </div>

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex size-9 items-center justify-center rounded-lg bg-accent-content text-accent-foreground">
                    <flux:icon name="heart" class="size-5" />
                </div>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">0</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Animales guardados') }}</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex size-9 items-center justify-center rounded-lg bg-accent-content text-accent-foreground">
                    <flux:icon name="document-text" class="size-5" />
                </div>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">0</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Solicitudes de adopción') }}</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex size-9 items-center justify-center rounded-lg bg-accent-content text-accent-foreground">
                    <flux:icon name="bell" class="size-5" />
                </div>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">0</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Notificaciones') }}</p>
            </div>
        </div>

        <div class="relative flex flex-1 flex-col items-center justify-center gap-3 rounded-xl border border-zinc-200 bg-white p-10 text-center dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex size-12 items-center justify-center rounded-full bg-accent-content text-accent-foreground">
                <flux:icon name="paper-airplane" class="size-6" />
            </div>
            <p class="pf-serif text-lg font-bold text-zinc-900 dark:text-white">{{ __('Aún no tienes actividad') }}</p>
            <p class="max-w-sm text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Cuando guardes un animal o envíes una solicitud de adopción, vas a verlo reflejado acá.') }}
            </p>
            <flux:button :href="route('mascotas')" variant="primary" wire:navigate>
                {{ __('Explorar animales en adopción') }}
            </flux:button>
        </div>
    </div>
</x-layouts::app>
