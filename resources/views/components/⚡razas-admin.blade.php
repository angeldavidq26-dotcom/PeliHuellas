<?php

use App\Models\Raza;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $filtroEspecie = 'todas';

    public ?int $razaId = null;

    public string $nombreRaza = '';

    public string $especie = 'perro';

    #[Computed]
    public function razas(): Collection
    {
        $query = Raza::query()->withCount('mascotas')->orderBy('nombre_raza');

        if ($this->filtroEspecie !== 'todas') {
            $query->where('especie', $this->filtroEspecie);
        }

        return $query->get();
    }

    public function abrirCrear(): void
    {
        $this->razaId = null;
        $this->nombreRaza = '';
        $this->especie = 'perro';

        Flux::modal('form-raza')->show();
    }

    public function abrirEditar(int $idRaza): void
    {
        $raza = Raza::findOrFail($idRaza);

        $this->razaId = $raza->id_raza;
        $this->nombreRaza = $raza->nombre_raza;
        $this->especie = $raza->especie;

        Flux::modal('form-raza')->show();
    }

    public function guardar(): void
    {
        $this->validate([
            'nombreRaza' => [
                'required',
                'string',
                'max:80',
                Rule::unique('raza', 'nombre_raza')->where('especie', $this->especie)->ignore($this->razaId, 'id_raza'),
            ],
            'especie' => ['required', Rule::in(['perro', 'gato'])],
        ]);

        if ($this->razaId) {
            Raza::whereKey($this->razaId)->update([
                'nombre_raza' => $this->nombreRaza,
                'especie' => $this->especie,
            ]);
        } else {
            Raza::create([
                'nombre_raza' => $this->nombreRaza,
                'especie' => $this->especie,
            ]);
        }

        unset($this->razas);

        Flux::toast(variant: 'success', text: __('Raza guardada.'));

        $this->dispatch('close-modal', name: 'form-raza');
    }

    public function eliminar(int $idRaza): void
    {
        $raza = Raza::withCount('mascotas')->findOrFail($idRaza);

        abort_if($raza->mascotas_count > 0, 422);

        $raza->delete();

        unset($this->razas);

        Flux::toast(variant: 'success', text: __('Raza eliminada.'));
    }
}; ?>

<div>
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Catálogo de razas') }}</h1>
            <p class="text-neutral-500">{{ __('Mantenimiento de razas disponibles en la plataforma') }}</p>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="abrirCrear">
            {{ __('Agregar raza') }}
        </flux:button>
    </div>

    <div class="mb-6 flex gap-2">
        @foreach (['todas' => __('Todas'), 'perro' => __('Perros'), 'gato' => __('Gatos')] as $valor => $etiqueta)
            <button
                type="button"
                wire:click="$set('filtroEspecie', '{{ $valor }}')"
                class="rounded-full px-4 py-1.5 text-sm font-semibold {{ $filtroEspecie === $valor ? 'bg-[#1f5c47] text-white' : 'border border-neutral-300 bg-white text-neutral-700 hover:bg-neutral-50' }}"
            >
                {{ $etiqueta }}
            </button>
        @endforeach
    </div>

    @foreach (['perro' => __('Perros'), 'gato' => __('Gatos')] as $especieValor => $especieLabel)
        @php $grupo = $this->razas->where('especie', $especieValor); @endphp
        @continue($grupo->isEmpty())

        <h2 class="pf-serif mb-3 text-lg font-bold">{{ $especieLabel }} ({{ $grupo->count() }})</h2>

        <div class="mb-8 overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Nombre') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Especie') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('En uso') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($grupo as $raza)
                        <tr wire:key="raza-{{ $raza->id_raza }}">
                            <td class="px-5 py-3 font-medium text-[#1f5c47]">{{ $raza->nombre_raza }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $raza->especie === 'perro' ? __('Perro') : __('Gato') }}</td>
                            <td class="px-5 py-3">
                                @if ($raza->mascotas_count > 0)
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                        {{ trans_choice(':count animal|:count animales', $raza->mascotas_count, ['count' => $raza->mascotas_count]) }}
                                    </span>
                                @else
                                    <span class="text-xs text-neutral-400">{{ __('Sin uso') }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <flux:button size="sm" variant="filled" wire:click="abrirEditar({{ $raza->id_raza }})">
                                        {{ __('Editar') }}
                                    </flux:button>
                                    <div>
                                        <flux:button
                                            size="sm"
                                            variant="danger"
                                            :disabled="$raza->mascotas_count > 0"
                                            wire:click="eliminar({{ $raza->id_raza }})"
                                            wire:confirm="{{ __('¿Eliminar la raza :nombre?', ['nombre' => $raza->nombre_raza]) }}"
                                        >
                                            {{ __('Eliminar') }}
                                        </flux:button>
                                        @if ($raza->mascotas_count > 0)
                                            <p class="mt-1 text-xs text-neutral-400">{{ __('Asignada a :count animales', ['count' => $raza->mascotas_count]) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    @if ($this->razas->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center text-neutral-500">
            {{ __('No hay razas registradas con este filtro.') }}
        </div>
    @endif

    <flux:modal name="form-raza" focusable class="max-w-md">
        <form wire:submit="guardar" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $razaId ? __('Editar raza') : __('Agregar raza') }}</flux:heading>
            </div>

            <flux:input wire:model="nombreRaza" :label="__('Nombre')" />
            @error('nombreRaza')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div>
                <flux:label>{{ __('Especie') }}</flux:label>
                <select wire:model="especie" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                    <option value="perro">{{ __('Perro') }}</option>
                    <option value="gato">{{ __('Gato') }}</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">{{ __('Guardar') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
