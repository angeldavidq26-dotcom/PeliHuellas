<?php

use App\Models\Auditoria;
use App\Models\Fundacion;
use App\Models\Usuario;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public ?int $seleccionadaId = null;

    public string $rechazoMotivo = '';

    public function mount(?int $fundacion = null): void
    {
        $this->seleccionadaId = $fundacion;
    }

    #[Computed]
    public function pendientes(): Collection
    {
        return Fundacion::query()
            ->where('estado_verificacion', 'pendiente')
            ->with('sedes')
            ->orderBy('fecha_registro')
            ->get();
    }

    #[Computed]
    public function revisadas(): Collection
    {
        return Fundacion::query()
            ->whereIn('estado_verificacion', ['aprobada', 'rechazada'])
            ->with('sedes')
            ->latest('fecha_registro')
            ->take(15)
            ->get();
    }

    #[Computed]
    public function seleccionada(): ?Fundacion
    {
        if ($this->seleccionadaId) {
            $encontrada = $this->pendientes->firstWhere('id_fundacion', $this->seleccionadaId)
                ?? $this->revisadas->firstWhere('id_fundacion', $this->seleccionadaId);

            if ($encontrada) {
                return $encontrada;
            }
        }

        return $this->pendientes->first();
    }

    public function seleccionar(int $idFundacion): void
    {
        $this->seleccionadaId = $idFundacion;
    }

    public function aprobar(int $idFundacion): void
    {
        $fundacion = Fundacion::query()->where('estado_verificacion', 'pendiente')->findOrFail($idFundacion);

        $fundacion->update([
            'estado_verificacion' => 'aprobada',
            'motivo_rechazo' => null,
        ]);

        $this->registrarAuditoria('fundacion.aprobada', $fundacion);

        $this->seleccionadaId = null;
        unset($this->pendientes, $this->revisadas, $this->seleccionada);

        Flux::toast(variant: 'success', text: __(':nombre fue aprobada.', ['nombre' => $fundacion->nombre]));
    }

    public function abrirRechazo(int $idFundacion): void
    {
        $this->seleccionadaId = $idFundacion;
        $this->rechazoMotivo = '';

        Flux::modal('rechazar-fundacion')->show();
    }

    public function confirmarRechazo(): void
    {
        $this->validate([
            'rechazoMotivo' => ['required', 'string', 'max:500'],
        ]);

        $fundacion = Fundacion::query()->where('estado_verificacion', 'pendiente')->findOrFail($this->seleccionadaId);

        $fundacion->update([
            'estado_verificacion' => 'rechazada',
            'motivo_rechazo' => $this->rechazoMotivo,
        ]);

        $this->registrarAuditoria('fundacion.rechazada', $fundacion, ['motivo' => $this->rechazoMotivo]);

        $this->seleccionadaId = null;
        $this->rechazoMotivo = '';
        unset($this->pendientes, $this->revisadas, $this->seleccionada);

        Flux::toast(variant: 'danger', text: __(':nombre fue rechazada.', ['nombre' => $fundacion->nombre]));

        $this->dispatch('close-modal', name: 'rechazar-fundacion');
    }

    protected function registrarAuditoria(string $accion, Fundacion $fundacion, array $datos = []): void
    {
        $usuario = Usuario::paraUser(auth()->user());

        Auditoria::registrar($accion, $fundacion, $usuario?->id_usuario, $datos);
    }
}; ?>

<div>
    <div class="mb-6">
        <h1 class="pf-serif text-3xl font-bold">{{ __('Verificaciones') }}</h1>
        <p class="text-neutral-500">{{ __('Revisa y aprueba o rechaza solicitudes de organizaciones') }}</p>
    </div>

    <div class="mb-6 flex items-start gap-3 rounded-xl border border-[#bfe0d0] bg-[#f2f8f5] px-5 py-4 text-sm text-[#1f5c47]">
        <flux:icon name="information-circle" variant="micro" class="size-5 shrink-0" />
        <p>
            <span class="font-semibold">{{ __('Aviso importante:') }}</span>
            {{ __('Una fundación aprobada puede publicar de inmediato y sus adoptantes pueden contactarla directamente. La verificación es la única protección del usuario contra organizaciones falsas.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[280px_1fr]">
        <div>
            <p class="mb-2 text-xs font-semibold tracking-widest text-neutral-500 uppercase">
                {{ __('Pendientes') }} ({{ $this->pendientes->count() }})
            </p>

            <div class="mb-4 space-y-2">
                @forelse ($this->pendientes as $fundacion)
                    @php $dias = $fundacion->fecha_registro->diffInDays(now()); @endphp
                    <button
                        type="button"
                        wire:click="seleccionar({{ $fundacion->id_fundacion }})"
                        wire:key="pendiente-{{ $fundacion->id_fundacion }}"
                        class="w-full rounded-xl border px-4 py-3 text-left {{ $this->seleccionada?->id_fundacion === $fundacion->id_fundacion ? 'border-[#1f5c47] bg-[#f2f8f5]' : 'border-neutral-200 bg-white hover:bg-neutral-50' }}"
                    >
                        <p class="font-semibold text-[#1f5c47]">{{ $fundacion->nombre }}</p>
                        <p class="text-xs {{ $dias >= 5 ? 'text-amber-600' : 'text-neutral-500' }}">
                            {{ trans_choice(':count día esperando|:count días esperando', $dias, ['count' => $dias]) }}
                            @if ($dias >= 5)
                                <flux:icon name="exclamation-triangle" variant="micro" class="inline size-3" />
                            @endif
                        </p>
                    </button>
                @empty
                    <p class="text-sm text-neutral-500">{{ __('No hay solicitudes pendientes.') }}</p>
                @endforelse
            </div>

            <details class="group" @if ($this->revisadas->isNotEmpty()) open @endif>
                <summary class="cursor-pointer text-xs font-semibold tracking-widest text-neutral-500 uppercase">
                    {{ __('Ya revisadas') }} ({{ $this->revisadas->count() }})
                </summary>
                <div class="mt-2 space-y-2">
                    @foreach ($this->revisadas as $fundacion)
                        <button
                            type="button"
                            wire:click="seleccionar({{ $fundacion->id_fundacion }})"
                            wire:key="revisada-{{ $fundacion->id_fundacion }}"
                            class="w-full rounded-xl border px-4 py-3 text-left {{ $this->seleccionada?->id_fundacion === $fundacion->id_fundacion ? 'border-[#1f5c47] bg-[#f2f8f5]' : 'border-neutral-200 bg-white hover:bg-neutral-50' }}"
                        >
                            <p class="font-semibold">{{ $fundacion->nombre }}</p>
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-semibold',
                                'bg-[#dcece4] text-[#234a3a]' => $fundacion->estado_verificacion === 'aprobada',
                                'bg-red-100 text-red-700' => $fundacion->estado_verificacion === 'rechazada',
                            ])>
                                {{ $fundacion->estado_verificacion === 'aprobada' ? __('Aprobada') : __('Rechazada') }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </details>
        </div>

        <div>
            @if (! $this->seleccionada)
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center text-neutral-500">
                    {{ __('No hay ninguna fundación para revisar.') }}
                </div>
            @else
                @php $fundacion = $this->seleccionada; @endphp
                <div class="rounded-xl border border-neutral-200 bg-white p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <img
                            src="{{ $fundacion->logo_url ?? 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=100&q=80' }}"
                            alt="{{ $fundacion->nombre }}"
                            class="size-12 rounded-lg object-cover"
                        >
                        <div>
                            <h2 class="pf-serif text-xl font-bold">{{ $fundacion->nombre }}</h2>
                            <p class="text-sm text-neutral-500">{{ $fundacion->nit }}</p>
                        </div>
                    </div>

                    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Correo') }}</p>
                            <p class="text-sm text-[#1f5c47]">{{ $fundacion->correo }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Teléfono') }}</p>
                            <p class="text-sm">{{ $fundacion->telefono }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Capacidad declarada') }}</p>
                            <p class="text-sm">{{ $fundacion->capacidad ? __(':n animales', ['n' => $fundacion->capacidad]) : __('Sin especificar') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Fecha de solicitud') }}</p>
                            <p class="text-sm">{{ $fundacion->fecha_registro->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    @if ($fundacion->descripcion)
                        <div class="mb-5">
                            <p class="text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Descripción') }}</p>
                            <p class="text-sm text-neutral-700">{{ $fundacion->descripcion }}</p>
                        </div>
                    @endif

                    @if ($fundacion->estado_verificacion === 'rechazada' && $fundacion->motivo_rechazo)
                        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <p class="font-semibold">{{ __('Motivo del rechazo') }}</p>
                            <p>{{ $fundacion->motivo_rechazo }}</p>
                        </div>
                    @endif

                    <div class="mb-6">
                        <p class="mb-2 text-xs font-semibold tracking-widest text-neutral-500 uppercase">{{ __('Documentos cargados') }}</p>
                        <div class="divide-y divide-neutral-100 rounded-lg border border-neutral-200">
                            <div class="flex items-center justify-between px-4 py-3 text-sm">
                                <span>{{ __('Certificado de existencia') }}</span>
                                @if ($fundacion->documento_certificado_url)
                                    <a href="{{ $fundacion->documento_certificado_url }}" target="_blank" class="font-medium text-[#1f5c47]">{{ __('Ver documento') }}</a>
                                @else
                                    <span class="text-neutral-400">{{ __('Sin cargar') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between px-4 py-3 text-sm">
                                <span>{{ __('Documento representante legal') }}</span>
                                @if ($fundacion->documento_representante_url)
                                    <a href="{{ $fundacion->documento_representante_url }}" target="_blank" class="font-medium text-[#1f5c47]">{{ __('Ver documento') }}</a>
                                @else
                                    <span class="text-neutral-400">{{ __('Sin cargar') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($fundacion->estado_verificacion === 'pendiente')
                        <div class="flex gap-3">
                            <flux:button variant="primary" class="flex-1" wire:click="aprobar({{ $fundacion->id_fundacion }})">
                                {{ __('Aprobar fundación') }}
                            </flux:button>
                            <flux:button variant="danger" class="flex-1" wire:click="abrirRechazo({{ $fundacion->id_fundacion }})">
                                {{ __('Rechazar') }}
                            </flux:button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <flux:modal name="rechazar-fundacion" focusable class="max-w-md">
        <form wire:submit="confirmarRechazo" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Rechazar fundación') }}</flux:heading>
                <flux:subheading>{{ __('Indica el motivo. La fundación recibirá esta información para corregir su solicitud.') }}</flux:subheading>
            </div>

            <flux:textarea wire:model="rechazoMotivo" :placeholder="__('Ej: La documentación del representante legal está vencida...')" rows="4" />
            @error('rechazoMotivo')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" type="submit">{{ __('Confirmar rechazo') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
