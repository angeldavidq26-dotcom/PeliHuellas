<?php

use App\Models\Fundacion;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Fundacion $fundacion;

    public $certificado = null;

    public $representante = null;

    public function mount(Fundacion $fundacion): void
    {
        $this->fundacion = $fundacion;
    }

    public function updatedCertificado(): void
    {
        $this->validateOnly('certificado', $this->rules());
        $this->guardarDocumento('certificado', 'documento_certificado_url');
    }

    public function updatedRepresentante(): void
    {
        $this->validateOnly('representante', $this->rules());
        $this->guardarDocumento('representante', 'documento_representante_url');
    }

    protected function guardarDocumento(string $propiedad, string $columna): void
    {
        /** @var \Illuminate\Http\UploadedFile $archivo */
        $archivo = $this->{$propiedad};
        $path = $archivo->store('fundaciones/documentos', 'public');

        $this->fundacion->update([
            $columna => Storage::disk('public')->url($path),
        ]);

        $this->{$propiedad} = null;

        Flux::toast(variant: 'success', text: __('Documento cargado correctamente.'));
    }

    public function enviarRevision(): void
    {
        if (! $this->fundacion->documentosCompletos()) {
            return;
        }

        $this->fundacion->update([
            'estado_verificacion' => $this->fundacion->estado_verificacion === 'aprobada' ? 'aprobada' : 'pendiente',
            'documentos_enviados_at' => now(),
        ]);

        Flux::toast(variant: 'success', text: __('Enviamos tus documentos para revisión.'));
    }

    protected function rules(): array
    {
        return [
            'certificado' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'representante' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}; ?>

<div>
    <h1 class="pf-serif mb-6 text-3xl font-bold">{{ __('Verificación de la organización') }}</h1>

    @if ($fundacion->estado_verificacion === 'aprobada')
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-[#bfe0d0] bg-[#f2f8f5] px-5 py-4 text-sm text-[#1f5c47]">
            <flux:icon name="check-circle" variant="micro" class="size-5 shrink-0" />
            <div>
                <p class="font-semibold">{{ __('Organización verificada') }}</p>
                <p class="text-[#3c6f5d]">{{ __('Tu fundación ya está verificada. Podés publicar animales y aparecer en el mapa de adopción.') }}</p>
            </div>
        </div>
    @elseif ($fundacion->estado_verificacion === 'rechazada')
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-900">
            <flux:icon name="x-circle" variant="micro" class="size-5 shrink-0 text-red-600" />
            <div>
                <p class="font-semibold">{{ __('Verificación rechazada') }}</p>
                <p class="text-red-700">{{ __('Revisá tus documentos y volvé a enviarlos para una nueva revisión.') }}</p>
            </div>
        </div>
    @elseif ($fundacion->documentos_enviados_at)
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">
            <flux:icon name="exclamation-triangle" variant="micro" class="size-5 shrink-0 text-amber-600" />
            <div>
                <p class="font-semibold">{{ __('Verificación pendiente') }}</p>
                <p class="text-amber-800">{{ __('Tu solicitud está en revisión. El equipo de PeliHuellas la revisará en los próximos 2-3 días hábiles.') }}</p>
            </div>
        </div>
    @else
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">
            <flux:icon name="exclamation-triangle" variant="micro" class="size-5 shrink-0 text-amber-600" />
            <div>
                <p class="font-semibold">{{ __('Verificación pendiente') }}</p>
                <p class="text-amber-800">{{ __('Subí los documentos requeridos para que empecemos a revisar tu organización.') }}</p>
            </div>
        </div>
    @endif

    @if ($fundacion->estado_verificacion !== 'aprobada')
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-600">
            <flux:icon name="clock" variant="micro" class="size-5 shrink-0 text-neutral-400" />
            {{ __('Mientras tu verificación esté pendiente no podés publicar animales ni aparecer en el mapa.') }}
        </div>
    @endif

    <div class="rounded-xl border border-neutral-200 bg-white p-6">
        <h2 class="pf-serif text-lg font-bold">{{ __('Documentos') }}</h2>
        <p class="mb-5 text-sm text-neutral-500">{{ __('Subí los archivos en PDF o imagen. Deben ser vigentes y legibles.') }}</p>

        <div class="divide-y divide-neutral-100">
            @foreach ([
                ['prop' => 'certificado', 'campo' => 'documento_certificado_url', 'label' => __('Certificado de existencia y representación legal')],
                ['prop' => 'representante', 'campo' => 'documento_representante_url', 'label' => __('Documento del representante legal')],
            ] as $doc)
                <div class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                    <div>
                        <p class="text-sm font-medium text-neutral-900">
                            {{ $doc['label'] }} <span class="text-red-500">*</span>
                        </p>
                        @if ($fundacion->{$doc['campo']})
                            <a href="{{ $fundacion->{$doc['campo']} }}" target="_blank" class="flex items-center gap-1 text-sm text-[#1f5c47]">
                                <flux:icon name="check-circle" variant="micro" class="size-4" />
                                {{ __('Archivo cargado — ver documento') }}
                            </a>
                        @else
                            <p class="text-sm text-neutral-400">{{ __('Sin archivo cargado') }}</p>
                        @endif
                        @error($doc['prop'])
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="shrink-0 cursor-pointer rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                        <span wire:loading.remove wire:target="{{ $doc['prop'] }}">
                            {{ $fundacion->{$doc['campo']} ? __('Cambiar archivo') : __('Subir archivo') }}
                        </span>
                        <span wire:loading wire:target="{{ $doc['prop'] }}">{{ __('Subiendo…') }}</span>
                        <input type="file" wire:model="{{ $doc['prop'] }}" accept=".pdf,image/png,image/jpeg" class="hidden">
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <flux:button
            wire:click="enviarRevision"
            variant="primary"
            :disabled="! $fundacion->documentosCompletos()"
        >
            {{ __('Enviar documentos para revisión') }}
        </flux:button>

        @unless ($fundacion->documentosCompletos())
            <p class="mt-2 text-sm text-neutral-500">{{ __('Cargá todos los documentos obligatorios para continuar.') }}</p>
        @endunless
    </div>
</div>
