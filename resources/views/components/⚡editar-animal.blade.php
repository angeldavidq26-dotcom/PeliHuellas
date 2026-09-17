<?php

use App\Models\Auditoria;
use App\Models\Caracteristica;
use App\Models\Fundacion;
use App\Models\Mascota;
use App\Models\Raza;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Fundacion $fundacion;

    public Mascota $mascota;

    /** @var array<int, mixed> */
    public array $nuevasFotos = [];

    public string $nombre = '';

    public string $especie = 'perro';

    public ?int $idRaza = null;

    public string $sexo = 'macho';

    public string $tamano = 'mediano';

    public ?int $edadAnos = null;

    public ?int $edadMeses = null;

    public ?string $pesoKg = null;

    public ?int $idSede = null;

    /** @var array<int, int> */
    public array $caracteristicasSeleccionadas = [];

    public string $descripcion = '';

    public bool $esterilizado = false;

    public bool $vacunado = false;

    public function mount(Fundacion $fundacion, Mascota $mascota): void
    {
        abort_unless($mascota->id_fundacion === $fundacion->id_fundacion, 404);

        $this->fundacion = $fundacion;
        $this->mascota = $mascota;

        $this->nombre = $mascota->nombre;
        $this->especie = $mascota->especie;
        $this->idRaza = $mascota->id_raza;
        $this->sexo = $mascota->sexo;
        $this->tamano = $mascota->tamano;
        $this->edadAnos = $mascota->edad_aprox_meses ? intdiv($mascota->edad_aprox_meses, 12) : null;
        $this->edadMeses = $mascota->edad_aprox_meses ? $mascota->edad_aprox_meses % 12 : null;
        $this->pesoKg = $mascota->peso_kg !== null ? (string) $mascota->peso_kg : null;
        $this->idSede = $mascota->id_sede;
        $this->descripcion = (string) $mascota->descripcion;
        $this->esterilizado = $mascota->esterilizado;
        $this->vacunado = $mascota->vacunado;
        $this->caracteristicasSeleccionadas = $mascota->caracteristicas->pluck('id_caracteristica')->all();
    }

    public function updatedEspecie(): void
    {
        $this->idRaza = null;
    }

    public function eliminarFotoExistente(int $idFoto): void
    {
        $foto = $this->mascota->fotos()->findOrFail($idFoto);
        $eraPrincipal = $foto->es_principal;
        $foto->delete();

        if ($eraPrincipal) {
            $this->mascota->fotos()->orderBy('orden')->first()?->update(['es_principal' => true]);
        }

        $this->mascota->refresh();
    }

    public function removeNuevaFoto(int $index): void
    {
        unset($this->nuevasFotos[$index]);
        $this->nuevasFotos = array_values($this->nuevasFotos);
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'especie' => ['required', Rule::in(['perro', 'gato'])],
            'idRaza' => ['nullable', 'integer', 'exists:raza,id_raza'],
            'sexo' => ['required', Rule::in(['macho', 'hembra'])],
            'tamano' => ['required', Rule::in(Mascota::TAMANOS)],
            'edadAnos' => ['nullable', 'integer', 'min:0', 'max:30'],
            'edadMeses' => ['nullable', 'integer', 'min:0', 'max:11'],
            'pesoKg' => ['nullable', 'numeric', 'min:0', 'max:120'],
            'idSede' => ['nullable', 'integer', 'exists:sede_fundacion,id_sede'],
            'descripcion' => ['required', 'string', 'min:20', 'max:2000'],
            'esterilizado' => ['boolean'],
            'vacunado' => ['boolean'],
            'nuevasFotos' => ['array'],
            'nuevasFotos.*' => ['image', 'max:10240'],
        ]);

        DB::transaction(function (): void {
            $edadMeses = ((int) ($this->edadAnos ?? 0) * 12) + (int) ($this->edadMeses ?? 0);

            $this->mascota->update([
                'nombre' => $this->nombre,
                'especie' => $this->especie,
                'id_raza' => $this->idRaza,
                'sexo' => $this->sexo,
                'tamano' => $this->tamano,
                'edad_aprox_meses' => $edadMeses > 0 ? $edadMeses : null,
                'peso_kg' => $this->pesoKg !== null && $this->pesoKg !== '' ? $this->pesoKg : null,
                'id_sede' => $this->idSede,
                'descripcion' => $this->descripcion,
                'esterilizado' => $this->esterilizado,
                'vacunado' => $this->vacunado,
            ]);

            $tienePrincipal = $this->mascota->fotos()->where('es_principal', true)->exists();
            $siguienteOrden = ($this->mascota->fotos()->max('orden') ?? 0) + 1;

            foreach ($this->nuevasFotos as $foto) {
                $path = $foto->store('mascotas', 'public');

                $this->mascota->fotos()->create([
                    'url' => Storage::disk('public')->url($path),
                    'orden' => $siguienteOrden++,
                    'es_principal' => ! $tienePrincipal,
                ]);

                $tienePrincipal = true;
            }

            $this->mascota->caracteristicas()->sync($this->caracteristicasSeleccionadas);

            Auditoria::registrar('mascota.actualizada', $this->mascota, $this->fundacion->id_usuario);
        });

        Flux::toast(variant: 'success', text: __(':nombre se actualizó correctamente.', ['nombre' => $this->nombre]));

        $this->redirectRoute('fundacion.mis-animales', navigate: true);
    }

    public function razas(): Collection
    {
        return Raza::query()->where('especie', $this->especie)->orderBy('nombre_raza')->get();
    }

    public function sedes(): Collection
    {
        return $this->fundacion->sedes;
    }

    public function caracteristicas(): Collection
    {
        return Caracteristica::query()->orderBy('nombre')->get();
    }
}; ?>

<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="pf-serif text-3xl font-bold">{{ __('Editar animal') }}</h1>
        <flux:button :href="route('fundacion.mis-animales')" variant="filled" wire:navigate>{{ __('Cancelar') }}</flux:button>
    </div>

    <form wire:submit="guardar" class="space-y-8 rounded-xl border border-neutral-200 bg-white p-8">
        <div>
            <flux:label>{{ __('Fotos') }}</flux:label>
            <div class="mt-2 grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ($mascota->fotos as $foto)
                    <div class="group relative aspect-square overflow-hidden rounded-lg border border-neutral-200">
                        <img src="{{ $foto->url }}" alt="" class="h-full w-full object-cover">
                        <button
                            type="button"
                            wire:click="eliminarFotoExistente({{ $foto->id }})"
                            wire:confirm="{{ __('¿Eliminar esta foto?') }}"
                            class="absolute top-1.5 right-1.5 flex size-6 items-center justify-center rounded-full bg-black/60 text-white"
                        >
                            <flux:icon name="x-mark" variant="micro" class="size-3.5" />
                        </button>
                        @if ($foto->es_principal)
                            <span class="absolute bottom-1.5 left-1.5 rounded-full bg-[#1f5c47] px-2 py-0.5 text-xs font-semibold text-white">
                                {{ __('Portada') }}
                            </span>
                        @endif
                    </div>
                @endforeach

                @foreach ($nuevasFotos as $index => $foto)
                    <div class="group relative aspect-square overflow-hidden rounded-lg border border-neutral-200">
                        <img src="{{ $foto->temporaryUrl() }}" alt="" class="h-full w-full object-cover">
                        <button
                            type="button"
                            wire:click="removeNuevaFoto({{ $index }})"
                            class="absolute top-1.5 right-1.5 flex size-6 items-center justify-center rounded-full bg-black/60 text-white"
                        >
                            <flux:icon name="x-mark" variant="micro" class="size-3.5" />
                        </button>
                    </div>
                @endforeach

                <label class="flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded-lg border-2 border-dashed border-neutral-300 text-center text-neutral-400 hover:border-[#1f5c47] hover:text-[#1f5c47]">
                    <flux:icon name="photo" variant="outline" class="size-6" />
                    <span class="text-xs">{{ __('Agregar foto') }}</span>
                    <input type="file" wire:model="nuevasFotos" multiple accept="image/png,image/jpeg" class="hidden">
                </label>
            </div>
            @error('nuevasFotos.*')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <flux:input wire:model="nombre" :label="__('Nombre')" />

            <div>
                <flux:label>{{ __('Especie') }}</flux:label>
                <div class="mt-2 flex gap-2">
                    @foreach (['perro' => __('Perro'), 'gato' => __('Gato')] as $value => $label)
                        <label class="relative flex-1">
                            <input type="radio" wire:model.live="especie" value="{{ $value }}" class="peer sr-only">
                            <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <flux:label>{{ __('Raza') }}</flux:label>
                <select wire:model="idRaza" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                    <option value="">{{ __('Sin especificar / mestizo') }}</option>
                    @foreach ($this->razas() as $raza)
                        <option value="{{ $raza->id_raza }}">{{ $raza->nombre_raza }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <flux:label>{{ __('Sexo') }}</flux:label>
                <div class="mt-2 flex gap-2">
                    @foreach (['macho' => __('Macho'), 'hembra' => __('Hembra')] as $value => $label)
                        <label class="relative flex-1">
                            <input type="radio" wire:model="sexo" value="{{ $value }}" class="peer sr-only">
                            <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="sm:col-span-2">
                <flux:label>{{ __('Tamaño') }}</flux:label>
                <div class="mt-2 grid grid-cols-4 gap-2">
                    @foreach (['pequeno' => __('Pequeño'), 'mediano' => __('Mediano'), 'grande' => __('Grande'), 'gigante' => __('Gigante')] as $value => $label)
                        <label class="relative">
                            <input type="radio" wire:model="tamano" value="{{ $value }}" class="peer sr-only">
                            <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <flux:label>{{ __('Edad aproximada') }}</flux:label>
                <div class="mt-2 flex items-center gap-2">
                    <flux:input wire:model="edadAnos" type="number" min="0" max="30" placeholder="0" />
                    <span class="text-sm text-neutral-500">{{ __('años') }}</span>
                    <flux:input wire:model="edadMeses" type="number" min="0" max="11" placeholder="0" />
                    <span class="text-sm text-neutral-500">{{ __('meses') }}</span>
                </div>
            </div>

            <flux:input wire:model="pesoKg" :label="__('Peso (kg)')" type="number" step="0.1" min="0" />

            @if ($this->sedes()->isNotEmpty())
                <div class="sm:col-span-2">
                    <flux:label>{{ __('Sede') }}</flux:label>
                    <select wire:model="idSede" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                        @foreach ($this->sedes() as $sede)
                            <option value="{{ $sede->id_sede }}">{{ $sede->nombre }} — {{ $sede->ciudad }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div>
            <flux:label>{{ __('¿Cómo describirías a este animal?') }}</flux:label>
            <p class="mb-4 text-sm text-neutral-500">{{ __('Elegí todas las que apliquen.') }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($this->caracteristicas() as $caracteristica)
                    <label class="relative">
                        <input
                            type="checkbox"
                            wire:model="caracteristicasSeleccionadas"
                            value="{{ $caracteristica->id_caracteristica }}"
                            class="peer sr-only"
                        >
                        <span class="block cursor-pointer rounded-full border border-neutral-300 px-4 py-2 text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">
                            {{ $caracteristica->nombre }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <flux:textarea wire:model="descripcion" :label="__('Descripción')" rows="6" />

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <flux:label>{{ __('¿Está esterilizado?') }}</flux:label>
                <div class="mt-2 flex gap-2">
                    <label class="relative flex-1">
                        <input type="radio" wire:model="esterilizado" value="1" class="peer sr-only">
                        <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">{{ __('Sí') }}</span>
                    </label>
                    <label class="relative flex-1">
                        <input type="radio" wire:model="esterilizado" value="0" class="peer sr-only">
                        <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">{{ __('No') }}</span>
                    </label>
                </div>
            </div>

            <div>
                <flux:label>{{ __('¿Tiene las vacunas al día?') }}</flux:label>
                <div class="mt-2 flex gap-2">
                    <label class="relative flex-1">
                        <input type="radio" wire:model="vacunado" value="1" class="peer sr-only">
                        <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">{{ __('Sí') }}</span>
                    </label>
                    <label class="relative flex-1">
                        <input type="radio" wire:model="vacunado" value="0" class="peer sr-only">
                        <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">{{ __('No') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-neutral-100 pt-6">
            <flux:button :href="route('fundacion.mis-animales')" variant="filled" wire:navigate>{{ __('Cancelar') }}</flux:button>
            <flux:button type="submit" variant="primary">{{ __('Guardar cambios') }}</flux:button>
        </div>
    </form>
</div>
