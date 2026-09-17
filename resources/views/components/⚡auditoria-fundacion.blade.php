<?php

use App\Models\Auditoria;
use App\Models\Fundacion;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Fundacion $fundacion;

    public function mount(Fundacion $fundacion): void
    {
        $this->fundacion = $fundacion;
    }

    #[Computed]
    public function entradas(): Collection
    {
        // Hoy solo el dueño de la fundación (fundacion.id_usuario) ejecuta
        // acciones administrativas, así que filtrar por ese usuario alcanza.
        // Si en el futuro varios miembros del equipo operan el panel, esto
        // necesita ampliarse a todos los usuarios de ese equipo.
        return Auditoria::where('id_usuario', $this->fundacion->id_usuario)
            ->latest('fecha')
            ->limit(200)
            ->get();
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="pf-serif text-3xl font-bold">{{ __('Auditoría') }}</h1>
        <p class="text-neutral-500">{{ __('Registro de acciones administrativas realizadas en el panel de :nombre.', ['nombre' => $fundacion->nombre]) }}</p>
    </div>

    @if ($this->entradas->isEmpty())
        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-12 text-center text-neutral-500">
            {{ __('Todavía no hay acciones registradas.') }}
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Fecha') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Acción') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Detalle') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('IP') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($this->entradas as $entrada)
                        <tr wire:key="aud-{{ $entrada->id }}">
                            <td class="px-5 py-3 text-neutral-600">{{ $entrada->fecha->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full bg-[#dcece4] px-2.5 py-1 text-xs font-semibold text-[#234a3a]">
                                    {{ $entrada->accion }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-neutral-500">
                                {{ $entrada->datos ? json_encode($entrada->datos) : '—' }}
                            </td>
                            <td class="px-5 py-3 text-neutral-400">{{ $entrada->ip ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
