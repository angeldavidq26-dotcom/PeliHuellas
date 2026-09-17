<?php

use App\Models\Adopcion;
use App\Models\Auditoria;
use App\Models\SeguimientoAdopcion;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Adopcion $adopcion;

    public string $estadoAnimal = 'bueno';

    public string $observaciones = '';

    public ?string $proximoSeguimiento = null;

    /** @var array<int, mixed> */
    public array $fotos = [];

    public function mount(Adopcion $adopcion): void
    {
        $this->adopcion = $adopcion;
        $this->proximoSeguimiento = now()->addDays(90)->format('Y-m-d');
    }

    #[Computed]
    public function seguimientos(): Collection
    {
        return $this->adopcion->seguimientos()->with('responsable')->get();
    }

    public function registrar(): void
    {
        $this->validate([
            'estadoAnimal' => ['required', Rule::in(SeguimientoAdopcion::ESTADOS_ANIMAL)],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'proximoSeguimiento' => ['nullable', 'date', 'after:today'],
            'fotos' => ['array', 'max:6'],
            'fotos.*' => ['image', 'max:10240'],
        ]);

        $urls = collect($this->fotos)
            ->map(fn ($foto) => Storage::disk('public')->url($foto->store('seguimientos', 'public')))
            ->all();

        $idResponsable = $this->adopcion->solicitud->mascota->fundacion->id_usuario;

        SeguimientoAdopcion::create([
            'id_adopcion' => $this->adopcion->id,
            'id_usuario_responsable' => $idResponsable,
            'fecha_seguimiento' => now(),
            'estado_animal' => $this->estadoAnimal,
            'observaciones' => $this->observaciones ?: null,
            'fotos' => $urls,
        ]);

        $this->adopcion->update([
            'proximo_seguimiento_at' => $this->proximoSeguimiento,
        ]);

        Auditoria::registrar('seguimiento.registrado', $this->adopcion, $idResponsable, ['estado_animal' => $this->estadoAnimal]);

        $this->reset(['estadoAnimal', 'observaciones', 'fotos']);
        $this->proximoSeguimiento = now()->addDays(90)->format('Y-m-d');

        unset($this->seguimientos);

        Flux::toast(variant: 'success', text: __('Seguimiento registrado.'));
    }
}; ?>

<div>
    @php
        $mascota = $adopcion->solicitud->mascota;
        $adoptante = $adopcion->solicitud->usuario;
    @endphp

    <div class="mb-8 flex items-center gap-4">
        <img
            src="{{ $mascota->fotoPrincipal?->url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=160&q=80' }}"
            alt="{{ $mascota->nombre }}"
            class="size-16 rounded-xl object-cover"
        >
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ $mascota->nombre }}</h1>
            <p class="text-neutral-500">
                {{ __('Adoptado por :nombre el :fecha', [
                    'nombre' => trim($adoptante->nombres.' '.$adoptante->primer_apellido),
                    'fecha' => \Illuminate\Support\Carbon::parse($adopcion->fecha_entrega)->translatedFormat('d M Y'),
                ]) }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="rounded-xl border border-neutral-200 bg-white p-6">
            <flux:heading size="lg" class="mb-4">{{ __('Registrar seguimiento') }}</flux:heading>

            <form wire:submit="registrar" class="space-y-4">
                <div>
                    <flux:label>{{ __('¿Cómo está el animal?') }}</flux:label>
                    <select wire:model="estadoAnimal" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                        <option value="excelente">{{ __('Excelente') }}</option>
                        <option value="bueno">{{ __('Bueno') }}</option>
                        <option value="regular">{{ __('Regular') }}</option>
                        <option value="preocupante">{{ __('Preocupante') }}</option>
                    </select>
                </div>

                <flux:textarea wire:model="observaciones" :label="__('Observaciones (opcional)')" rows="4" />

                <div>
                    <flux:label>{{ __('Fotos (opcional)') }}</flux:label>
                    <input type="file" wire:model="fotos" multiple accept="image/png,image/jpeg" class="mt-2 block w-full text-sm">
                    @error('fotos.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if (! empty($fotos))
                        <div class="mt-3 flex gap-2">
                            @foreach ($fotos as $foto)
                                <img src="{{ $foto->temporaryUrl() }}" alt="" class="size-16 rounded-lg object-cover">
                            @endforeach
                        </div>
                    @endif
                </div>

                <flux:input wire:model="proximoSeguimiento" type="date" :label="__('Próximo seguimiento')" />

                <flux:button type="submit" variant="primary" class="w-full">{{ __('Guardar seguimiento') }}</flux:button>
            </form>
        </div>

        <div>
            <flux:heading size="lg" class="mb-4">{{ __('Historial') }}</flux:heading>

            @if ($this->seguimientos->isEmpty())
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-8 text-center text-neutral-500">
                    {{ __('Todavía no se registró ningún seguimiento.') }}
                </div>
            @else
                <ul class="space-y-4">
                    @foreach ($this->seguimientos as $seguimiento)
                        <li wire:key="seg-{{ $seguimiento->id }}" class="rounded-xl border border-neutral-200 bg-white p-4">
                            <div class="flex items-center justify-between">
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-semibold capitalize',
                                    'bg-[#dcece4] text-[#234a3a]' => in_array($seguimiento->estado_animal, ['excelente', 'bueno'], true),
                                    'bg-amber-100 text-amber-700' => $seguimiento->estado_animal === 'regular',
                                    'bg-red-100 text-red-700' => $seguimiento->estado_animal === 'preocupante',
                                ])>
                                    {{ $seguimiento->estado_animal }}
                                </span>
                                <span class="text-xs text-neutral-400">
                                    {{ $seguimiento->fecha_seguimiento->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            @if ($seguimiento->observaciones)
                                <p class="mt-2 text-sm text-neutral-600">{{ $seguimiento->observaciones }}</p>
                            @endif
                            @if (! empty($seguimiento->fotos))
                                <div class="mt-3 flex gap-2">
                                    @foreach ($seguimiento->fotos as $url)
                                        <img src="{{ $url }}" alt="" class="size-16 rounded-lg object-cover">
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
