<?php

use App\Models\Favorito;
use App\Models\Mascota;
use App\Models\Usuario;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Mascota $mascota;

    public bool $compacto = true;

    public function mount(Mascota $mascota, bool $compacto = true): void
    {
        $this->mascota = $mascota;
        $this->compacto = $compacto;
    }

    #[Computed]
    public function usuario(): ?Usuario
    {
        return auth()->check() ? Usuario::paraUser(auth()->user()) : null;
    }

    #[Computed]
    public function esFavorito(): bool
    {
        return $this->usuario
            ? Favorito::where('id_usuario', $this->usuario->id_usuario)
                ->where('id_mascota', $this->mascota->id_mascota)
                ->exists()
            : false;
    }

    public function toggle(): void
    {
        if (! $this->usuario) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $favorito = Favorito::where('id_usuario', $this->usuario->id_usuario)
            ->where('id_mascota', $this->mascota->id_mascota)
            ->first();

        if ($favorito) {
            $favorito->delete();
        } else {
            Favorito::create([
                'id_usuario' => $this->usuario->id_usuario,
                'id_mascota' => $this->mascota->id_mascota,
                'fecha_guardado' => now(),
            ]);
        }

        unset($this->esFavorito);
    }
}; ?>

<div>
    @if ($compacto)
        <button
            type="button"
            wire:click.prevent.stop="toggle"
            title="{{ $this->esFavorito ? __('Quitar de favoritos') : __('Guardar en favoritos') }}"
            class="flex size-9 items-center justify-center rounded-full bg-white/90 shadow transition hover:bg-white"
        >
            <flux:icon
                name="heart"
                :variant="$this->esFavorito ? 'solid' : 'outline'"
                class="size-4 {{ $this->esFavorito ? 'text-red-500' : 'text-neutral-500' }}"
            />
        </button>
    @else
        <flux:button wire:click="toggle" variant="{{ $this->esFavorito ? 'primary' : 'filled' }}" icon="heart" class="w-full">
            {{ $this->esFavorito ? __('Guardado en favoritos') : __('Guardar en favoritos') }}
        </flux:button>
    @endif
</div>
