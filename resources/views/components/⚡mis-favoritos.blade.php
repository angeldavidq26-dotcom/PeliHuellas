<?php

use App\Models\Favorito;
use App\Models\Usuario;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function usuario(): ?Usuario
    {
        return auth()->check() ? Usuario::paraUser(auth()->user()) : null;
    }

    #[Computed]
    public function favoritos(): Collection
    {
        if (! $this->usuario) {
            return collect();
        }

        return $this->usuario->favoritos()
            ->with(['mascota.fundacion', 'mascota.sede', 'mascota.fotoPrincipal'])
            ->latest('fecha_guardado')
            ->get()
            ->filter(fn (Favorito $favorito) => $favorito->mascota !== null);
    }

    public function quitar(int $idMascota): void
    {
        Favorito::where('id_usuario', $this->usuario?->id_usuario)
            ->where('id_mascota', $idMascota)
            ->delete();

        unset($this->favoritos);

        Flux::toast(variant: 'success', text: __('Se quitó de tus favoritos.'));
    }
}; ?>

<div>
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Mis favoritos') }}</h1>
            <p class="text-neutral-500">{{ __('Animales que guardaste para decidir después.') }}</p>
        </div>
        <flux:button :href="route('mascotas')" variant="primary" wire:navigate>{{ __('Ver animales') }}</flux:button>
    </div>

    @if ($this->favoritos->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('Todavía no guardaste ningún animal como favorito.') }}
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($this->favoritos as $favorito)
                @php $mascota = $favorito->mascota; @endphp
                <div wire:key="fav-{{ $favorito->id }}" class="flex gap-3 rounded-xl border border-neutral-200 bg-white p-4">
                    <a href="{{ route('mascotas.show', $mascota) }}" wire:navigate class="shrink-0">
                        <img
                            src="{{ $mascota->fotoPrincipal?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=200&q=80' }}"
                            alt="{{ $mascota->nombre }}"
                            class="size-16 rounded-lg object-cover"
                        >
                    </a>
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <a href="{{ route('mascotas.show', $mascota) }}" wire:navigate class="font-semibold hover:underline">
                                {{ $mascota->nombre }}
                            </a>
                            <button type="button" wire:click="quitar({{ $mascota->id_mascota }})" title="{{ __('Quitar de favoritos') }}">
                                <flux:icon name="heart" variant="solid" class="size-4 text-red-500" />
                            </button>
                        </div>
                        <p class="text-sm text-neutral-500">
                            {{ $mascota->fundacion?->nombre }} · {{ $mascota->sede?->ciudad }}
                        </p>
                        <span @class([
                            'mt-1 inline-block rounded-full px-2 py-0.5 text-xs font-semibold',
                            'bg-[#dcece4] text-[#234a3a]' => $mascota->estado === 'disponible',
                            'bg-neutral-100 text-neutral-600' => $mascota->estado !== 'disponible',
                        ])>
                            {{ ucfirst($mascota->estado) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
