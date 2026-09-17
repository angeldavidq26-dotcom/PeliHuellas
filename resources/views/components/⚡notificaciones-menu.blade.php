<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function notificaciones(): Collection
    {
        return auth()->user()->notifications()->latest()->take(10)->get();
    }

    #[Computed]
    public function noLeidas(): int
    {
        return auth()->user()->unreadNotifications()->count();
    }

    public function marcarLeida(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();

        unset($this->notificaciones, $this->noLeidas);
    }

    public function marcarTodasLeidas(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        unset($this->notificaciones, $this->noLeidas);
    }
}; ?>

<flux:dropdown position="bottom" align="end">
    <button type="button" class="relative flex size-10 items-center justify-center rounded-lg hover:bg-neutral-100">
        <flux:icon name="bell" variant="outline" class="size-5 text-neutral-600" />
        @if ($this->noLeidas > 0)
            <span class="absolute top-1 right-1 flex size-4 items-center justify-center rounded-full bg-red-600 text-[9px] font-semibold text-white">
                {{ $this->noLeidas > 9 ? '9+' : $this->noLeidas }}
            </span>
        @endif
    </button>

    <flux:menu class="w-80">
        <div class="flex items-center justify-between px-3 py-2">
            <span class="text-sm font-semibold">{{ __('Notificaciones') }}</span>
            @if ($this->noLeidas > 0)
                <button type="button" wire:click="marcarTodasLeidas" class="text-xs text-[#1f5c47] hover:underline">
                    {{ __('Marcar todas como leídas') }}
                </button>
            @endif
        </div>

        @if ($this->notificaciones->isEmpty())
            <p class="px-3 py-4 text-center text-sm text-neutral-500">{{ __('No tenés notificaciones.') }}</p>
        @else
            <div class="max-h-96 divide-y divide-neutral-100 overflow-y-auto">
                @foreach ($this->notificaciones as $notificacion)
                    <a
                        href="{{ $notificacion->data['url'] ?? '#' }}"
                        wire:click="marcarLeida('{{ $notificacion->id }}')"
                        wire:navigate
                        class="block px-3 py-3 text-sm {{ $notificacion->read_at ? 'bg-white' : 'bg-[#f2f8f5]' }} hover:bg-neutral-50"
                    >
                        <p class="font-medium text-neutral-900">{{ $notificacion->data['title'] ?? '' }}</p>
                        <p class="mt-0.5 text-neutral-600">{{ $notificacion->data['body'] ?? '' }}</p>
                        <p class="mt-1 text-xs text-neutral-400">{{ $notificacion->created_at->diffForHumans() }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </flux:menu>
</flux:dropdown>
