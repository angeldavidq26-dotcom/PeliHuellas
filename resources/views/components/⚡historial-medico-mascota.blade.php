<?php

use App\Models\Auditoria;
use App\Models\Fundacion;
use App\Models\HistorialMedico;
use App\Models\Mascota;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Fundacion $fundacion;

    public Mascota $mascota;

    public string $tipo = 'vacuna';

    public string $fecha = '';

    public string $veterinario = '';

    public string $observaciones = '';

    public $documento = null;

    public function mount(Fundacion $fundacion, Mascota $mascota): void
    {
        abort_unless($mascota->id_fundacion === $fundacion->id_fundacion, 404);

        $this->fundacion = $fundacion;
        $this->mascota = $mascota;
        $this->fecha = now()->format('Y-m-d');
    }

    #[Computed]
    public function registros(): Collection
    {
        return $this->mascota->historialMedico;
    }

    public function registrar(): void
    {
        $this->validate([
            'tipo' => ['required', Rule::in(HistorialMedico::TIPOS)],
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'veterinario' => ['nullable', 'string', 'max:160'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'documento' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $documentoUrl = $this->documento
            ? Storage::disk('public')->url($this->documento->store('historial-medico', 'public'))
            : null;

        HistorialMedico::create([
            'id_mascota' => $this->mascota->id_mascota,
            'tipo' => $this->tipo,
            'fecha' => $this->fecha,
            'veterinario' => $this->veterinario ?: null,
            'documento_url' => $documentoUrl,
            'observaciones' => $this->observaciones ?: null,
            'id_usuario_responsable' => $this->fundacion->id_usuario,
            'fecha_registro' => now(),
        ]);

        Auditoria::registrar('historial_medico.registrado', $this->mascota, $this->fundacion->id_usuario, ['tipo' => $this->tipo]);

        $this->reset(['veterinario', 'observaciones', 'documento']);
        $this->tipo = 'vacuna';
        $this->fecha = now()->format('Y-m-d');

        unset($this->registros);
        $this->mascota->refresh();

        Flux::toast(variant: 'success', text: __('Registro médico agregado.'));
    }
}; ?>

<div class="mt-8 rounded-xl border border-neutral-200 bg-white p-8">
    <flux:heading size="lg" class="mb-1">{{ __('Historial médico') }}</flux:heading>
    <p class="mb-6 text-sm text-neutral-500">{{ __('Vacunas, desparasitaciones, cirugías y consultas de :nombre.', ['nombre' => $mascota->nombre]) }}</p>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <form wire:submit="registrar" class="space-y-4">
            <div>
                <flux:label>{{ __('Tipo') }}</flux:label>
                <select wire:model="tipo" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                    <option value="vacuna">{{ __('Vacuna') }}</option>
                    <option value="desparasitacion">{{ __('Desparasitación') }}</option>
                    <option value="cirugia">{{ __('Cirugía') }}</option>
                    <option value="consulta">{{ __('Consulta') }}</option>
                    <option value="otro">{{ __('Otro') }}</option>
                </select>
            </div>

            <flux:input wire:model="fecha" type="date" :label="__('Fecha')" />
            <flux:input wire:model="veterinario" :label="__('Veterinario / clínica (opcional)')" />
            <flux:textarea wire:model="observaciones" :label="__('Observaciones (opcional)')" rows="3" />

            <div>
                <flux:label>{{ __('Documento adjunto (opcional)') }}</flux:label>
                <input type="file" wire:model="documento" accept=".pdf,image/png,image/jpeg" class="mt-2 block w-full text-sm">
                @error('documento')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <flux:button type="submit" variant="primary" class="w-full">{{ __('Agregar registro') }}</flux:button>
        </form>

        <div>
            @if ($this->registros->isEmpty())
                <div class="rounded-xl border border-dashed border-neutral-300 p-8 text-center text-sm text-neutral-500">
                    {{ __('Todavía no hay registros médicos.') }}
                </div>
            @else
                <ul class="max-h-[26rem] space-y-3 overflow-y-auto pr-1">
                    @foreach ($this->registros as $registro)
                        <li wire:key="hm-{{ $registro->id }}" class="rounded-lg border border-neutral-200 p-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="rounded-full bg-[#dcece4] px-2.5 py-1 text-xs font-semibold text-[#234a3a] capitalize">
                                    {{ str($registro->tipo)->replace('_', ' ') }}
                                </span>
                                <span class="text-xs text-neutral-400">{{ $registro->fecha->translatedFormat('d M Y') }}</span>
                            </div>
                            @if ($registro->veterinario)
                                <p class="mt-1.5 text-neutral-600">{{ $registro->veterinario }}</p>
                            @endif
                            @if ($registro->observaciones)
                                <p class="mt-1 text-neutral-500">{{ $registro->observaciones }}</p>
                            @endif
                            @if ($registro->documento_url)
                                <a href="{{ $registro->documento_url }}" target="_blank" class="mt-1.5 inline-flex items-center gap-1 text-xs font-medium text-[#1f5c47] hover:underline">
                                    <flux:icon name="paper-clip" variant="micro" class="size-3.5" />
                                    {{ __('Ver documento') }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
