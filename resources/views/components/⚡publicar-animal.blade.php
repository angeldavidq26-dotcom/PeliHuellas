<?php

use App\Models\Caracteristica;
use App\Models\Fundacion;
use App\Models\Mascota;
use App\Models\Raza;
use App\Models\SedeFundacion;
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

    public int $currentStep = 1;

    public int $maxStepReached = 1;

    /** @var array<int, mixed> */
    public array $fotos = [];

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

    public function mount(Fundacion $fundacion): void
    {
        $this->fundacion = $fundacion;
        $this->idSede = $fundacion->sedes->firstWhere('es_principal', true)?->id_sede
            ?? $fundacion->sedes->first()?->id_sede;
    }

    public function updatedEspecie(): void
    {
        $this->idRaza = null;
    }

    public function removeFoto(int $index): void
    {
        unset($this->fotos[$index]);
        $this->fotos = array_values($this->fotos);
    }

    public function next(): void
    {
        $this->validate($this->rulesForStep($this->currentStep));

        $this->currentStep = min(5, $this->currentStep + 1);
        $this->maxStepReached = max($this->maxStepReached, $this->currentStep);
    }

    public function back(): void
    {
        $this->currentStep = max(1, $this->currentStep - 1);
    }

    public function goTo(int $step): void
    {
        if ($step <= $this->maxStepReached) {
            $this->currentStep = $step;
        }
    }

    public function submit(): void
    {
        $this->validate(array_merge(
            $this->rulesForStep(1),
            $this->rulesForStep(2),
            $this->rulesForStep(3),
            $this->rulesForStep(4),
            $this->rulesForStep(5),
        ));

        $mascota = DB::transaction(function (): Mascota {
            $edadMeses = ((int) ($this->edadAnos ?? 0) * 12) + (int) ($this->edadMeses ?? 0);

            $mascota = Mascota::create([
                'id_fundacion' => $this->fundacion->id_fundacion,
                'id_sede' => $this->idSede,
                'id_raza' => $this->idRaza,
                'nombre' => $this->nombre,
                'especie' => $this->especie,
                'sexo' => $this->sexo,
                'tamano' => $this->tamano,
                'edad_aprox_meses' => $edadMeses > 0 ? $edadMeses : null,
                'peso_kg' => $this->pesoKg !== null && $this->pesoKg !== '' ? $this->pesoKg : null,
                'descripcion' => $this->descripcion,
                'esterilizado' => $this->esterilizado,
                'vacunado' => $this->vacunado,
                'fecha_ingreso' => now(),
                'estado' => 'disponible',
            ]);

            foreach ($this->fotos as $index => $foto) {
                $path = $foto->store('mascotas', 'public');

                $mascota->fotos()->create([
                    'url' => Storage::disk('public')->url($path),
                    'orden' => $index + 1,
                    'es_principal' => $index === 0,
                ]);
            }

            if ($this->caracteristicasSeleccionadas !== []) {
                $mascota->caracteristicas()->attach($this->caracteristicasSeleccionadas);
            }

            return $mascota;
        });

        Flux::toast(variant: 'success', text: __(':nombre se publicó correctamente.', ['nombre' => $mascota->nombre]));

        $this->redirectRoute('fundacion.mis-animales', navigate: true);
    }

    protected function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'fotos' => ['required', 'array', 'min:1'],
                'fotos.*' => ['image', 'max:10240'],
            ],
            2 => [
                'nombre' => ['required', 'string', 'max:80'],
                'especie' => ['required', Rule::in(['perro', 'gato'])],
                'idRaza' => ['nullable', 'integer', 'exists:raza,id_raza'],
                'sexo' => ['required', Rule::in(['macho', 'hembra'])],
                'tamano' => ['required', Rule::in(Mascota::TAMANOS)],
                'edadAnos' => ['nullable', 'integer', 'min:0', 'max:30'],
                'edadMeses' => ['nullable', 'integer', 'min:0', 'max:11'],
                'pesoKg' => ['nullable', 'numeric', 'min:0', 'max:120'],
                'idSede' => ['nullable', 'integer', 'exists:sede_fundacion,id_sede'],
            ],
            3 => [
                'caracteristicasSeleccionadas' => ['array'],
                'caracteristicasSeleccionadas.*' => ['integer', 'exists:caracteristica,id_caracteristica'],
            ],
            4 => [
                'descripcion' => ['required', 'string', 'min:20', 'max:2000'],
            ],
            5 => [
                'esterilizado' => ['boolean'],
                'vacunado' => ['boolean'],
            ],
            default => [],
        };
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
    @php
        $pasos = [
            1 => __('Fotos'),
            2 => __('Datos básicos'),
            3 => __('Características'),
            4 => __('Descripción'),
            5 => __('Salud'),
        ];
    @endphp

    <h1 class="pf-serif mb-6 text-3xl font-bold">{{ __('Publicar animal') }}</h1>

    <div class="mb-8 flex flex-wrap items-center gap-2">
        @foreach ($pasos as $numero => $etiqueta)
            <button
                type="button"
                wire:click="goTo({{ $numero }})"
                @if ($numero > $maxStepReached) disabled @endif
                @class([
                    'flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-semibold transition',
                    'bg-[#1f5c47] text-white' => $currentStep === $numero,
                    'bg-[#dcece4] text-[#1f5c47]' => $currentStep !== $numero && $numero < $maxStepReached,
                    'bg-neutral-100 text-neutral-400' => $currentStep !== $numero && $numero >= $maxStepReached,
                    'cursor-default' => $numero > $maxStepReached,
                ])
            >
                @if ($numero < $maxStepReached)
                    <flux:icon name="check" variant="micro" class="size-3.5" />
                @else
                    <span>{{ $numero }}</span>
                @endif
                {{ $etiqueta }}
            </button>
            @if (! $loop->last)
                <span class="h-px w-6 bg-neutral-200"></span>
            @endif
        @endforeach
    </div>

    <div class="rounded-xl border border-neutral-200 bg-white p-8">
        {{-- Paso 1: Fotos --}}
        @if ($currentStep === 1)
            <div
                x-data="{ over: false }"
                x-on:dragover.prevent="over = true"
                x-on:dragleave.prevent="over = false"
                x-on:drop.prevent="over = false"
                @class([
                    'rounded-xl border-2 border-dashed px-6 py-16 text-center transition',
                    'border-[#1f5c47] bg-[#f2f8f5]' => true,
                ])
                x-bind:class="over ? 'border-[#1f5c47] bg-[#f2f8f5]' : 'border-neutral-300'"
            >
                <label class="cursor-pointer">
                    <flux:icon name="photo" variant="outline" class="mx-auto mb-4 size-10 text-neutral-400" />
                    <p class="mb-1 font-semibold">{{ __('Arrastrá las fotos aquí') }}</p>
                    <p class="text-sm text-neutral-500">{{ __('o hacé clic para seleccionar — JPG, PNG hasta 10 MB por imagen') }}</p>
                    <input type="file" wire:model="fotos" multiple accept="image/png,image/jpeg" class="hidden">
                </label>
            </div>

            <div wire:loading wire:target="fotos" class="mt-4 text-sm text-neutral-500">
                {{ __('Subiendo fotos…') }}
            </div>

            @error('fotos')
                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('fotos.*')
                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if (! empty($fotos))
                <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach ($fotos as $index => $foto)
                        <div class="group relative aspect-square overflow-hidden rounded-lg border border-neutral-200">
                            <img src="{{ $foto->temporaryUrl() }}" alt="" class="h-full w-full object-cover">
                            <button
                                type="button"
                                wire:click="removeFoto({{ $index }})"
                                class="absolute top-1.5 right-1.5 flex size-6 items-center justify-center rounded-full bg-black/60 text-white"
                            >
                                <flux:icon name="x-mark" variant="micro" class="size-3.5" />
                            </button>
                            @if ($index === 0)
                                <span class="absolute bottom-1.5 left-1.5 rounded-full bg-[#1f5c47] px-2 py-0.5 text-xs font-semibold text-white">
                                    {{ __('Portada') }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- Paso 2: Datos básicos --}}
        @if ($currentStep === 2)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <flux:input wire:model="nombre" :label="__('Nombre')" placeholder="Zeus" />

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

                <flux:input wire:model="pesoKg" :label="__('Peso (kg)')" type="number" step="0.1" min="0" placeholder="12.5" />

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
        @endif

        {{-- Paso 3: Características --}}
        @if ($currentStep === 3)
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
        @endif

        {{-- Paso 4: Descripción --}}
        @if ($currentStep === 4)
            <div>
                <flux:textarea
                    wire:model="descripcion"
                    :label="__('Descripción')"
                    :description="__('Contá su historia, personalidad y qué tipo de hogar sería ideal para él o ella.')"
                    rows="8"
                    placeholder="{{ __('Ej: Zeus es un perro juguetón y muy cariñoso que llegó a la fundación hace un año...') }}"
                />
            </div>
        @endif

        {{-- Paso 5: Salud --}}
        @if ($currentStep === 5)
            <div class="space-y-6">
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
        @endif
    </div>

    <div class="mt-6 flex items-center justify-between">
        @if ($currentStep === 1)
            <flux:button :href="route('fundacion.panel')" variant="filled" wire:navigate>{{ __('Cancelar') }}</flux:button>
        @else
            <flux:button wire:click="back" variant="filled">{{ __('Atrás') }}</flux:button>
        @endif

        @if ($currentStep < 5)
            <flux:button wire:click="next" variant="primary">{{ __('Continuar') }}</flux:button>
        @else
            <flux:button wire:click="submit" variant="primary">{{ __('Publicar animal') }}</flux:button>
        @endif
    </div>
</div>
