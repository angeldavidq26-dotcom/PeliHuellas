<?php

use App\Models\Fundacion;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {
    public Fundacion $fundacion;

    public string $nombre = '';

    public string $nit = '';

    public string $correo = '';

    public string $telefono = '';

    public function mount(Fundacion $fundacion): void
    {
        $this->fundacion = $fundacion;
        $this->nombre = $fundacion->nombre;
        $this->nit = $fundacion->nit;
        $this->correo = $fundacion->correo;
        $this->telefono = $fundacion->telefono;
    }

    public function guardar(): void
    {
        $verificada = $this->fundacion->estado_verificacion === 'aprobada';

        $data = $this->validate([
            'nombre' => ['required', 'string', 'max:160'],
            'nit' => [
                'required', 'string', 'max:30',
                Rule::unique('fundacion', 'nit')->ignore($this->fundacion->id_fundacion, 'id_fundacion'),
            ],
            'correo' => ['required', 'email', 'max:160'],
            'telefono' => ['required', 'string', 'max:25'],
        ]);

        if ($verificada) {
            // El NIT queda protegido mientras la fundación esté verificada.
            unset($data['nit']);
        }

        $this->fundacion->update($data);

        Flux::toast(variant: 'success', text: __('Cambios guardados correctamente.'));
    }
}; ?>

<div>
    <h1 class="pf-serif mb-6 text-3xl font-bold">{{ __('Configuración') }}</h1>

    <div class="rounded-xl border border-neutral-200 bg-white p-6">
        <p class="mb-6 text-sm text-neutral-500">{{ __('Datos de la fundación, sedes y verificación.') }}</p>

        <div class="max-w-lg space-y-5">
            <flux:input wire:model="nombre" :label="__('Nombre de la fundación')" />

            <div>
                <flux:input
                    wire:model="nit"
                    :label="__('NIT')"
                    :disabled="$fundacion->estado_verificacion === 'aprobada'"
                />
                @if ($fundacion->estado_verificacion === 'aprobada')
                    <p class="mt-1 text-xs text-neutral-400">{{ __('Solo lectura mientras esté verificada.') }}</p>
                @endif
            </div>

            <flux:input wire:model="correo" type="email" :label="__('Correo de contacto')" />

            <flux:input wire:model="telefono" :label="__('Teléfono')" />
        </div>

        <div class="mt-8">
            <flux:button wire:click="guardar" variant="primary">{{ __('Guardar cambios') }}</flux:button>
        </div>
    </div>
</div>
