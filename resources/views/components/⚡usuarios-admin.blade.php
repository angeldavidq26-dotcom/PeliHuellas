<?php

use App\Models\Fundacion;
use App\Models\PerfilAdoptante;
use App\Models\User;
use App\Models\Usuario;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $busqueda = '';

    public string $estado = 'todos';

    public string $rol = 'todos';

    #[Computed]
    public function usuarios(): Collection
    {
        $query = Usuario::query()->orderByDesc('fecha_registro');

        if ($this->busqueda !== '') {
            $termino = '%'.$this->busqueda.'%';
            $query->where(function ($q) use ($termino) {
                $q->where('nombres', 'like', $termino)
                    ->orWhere('primer_apellido', 'like', $termino)
                    ->orWhere('correo', 'like', $termino);
            });
        }

        if ($this->estado !== 'todos') {
            $query->where('estado', $this->estado);
        }

        $usuarios = $query->get();

        $idsUsuario = $usuarios->pluck('id_usuario');
        $idsUser = $usuarios->pluck('id_user')->filter();

        $fundacionesPorUsuario = Fundacion::query()->whereIn('id_usuario', $idsUsuario)->pluck('id_usuario')->flip();
        $adoptantesPorUsuario = PerfilAdoptante::query()->whereIn('id_usuario', $idsUsuario)->pluck('id_usuario')->flip();
        $adminsPorUser = User::query()->whereIn('id', $idsUser)->where('role', 'administracion')->pluck('id')->flip();

        $usuarios = $usuarios->map(function (Usuario $usuario) use ($fundacionesPorUsuario, $adoptantesPorUsuario, $adminsPorUser) {
            $roles = [];

            if ($usuario->id_user && $adminsPorUser->has($usuario->id_user)) {
                $roles[] = 'admin';
            }
            if ($fundacionesPorUsuario->has($usuario->id_usuario)) {
                $roles[] = 'fundacion';
            }
            if ($adoptantesPorUsuario->has($usuario->id_usuario)) {
                $roles[] = 'adoptante';
            }

            $usuario->roles_calculados = $roles;

            return $usuario;
        });

        if ($this->rol !== 'todos') {
            $usuarios = $usuarios->filter(fn (Usuario $u) => in_array($this->rol, $u->roles_calculados, true))->values();
        }

        return $usuarios;
    }

    public function cambiarEstado(int $idUsuario, string $nuevoEstado): void
    {
        abort_unless(in_array($nuevoEstado, ['activo', 'inactivo', 'suspendido'], true), 422);

        $usuario = Usuario::findOrFail($idUsuario);
        $usuario->update(['estado' => $nuevoEstado]);

        unset($this->usuarios);

        Flux::toast(variant: 'success', text: __('Estado de :nombre actualizado.', ['nombre' => $usuario->nombres]));
    }
}; ?>

<div>
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="pf-serif text-3xl font-bold">{{ __('Gestión de usuarios') }}</h1>
            <p class="text-neutral-500">{{ __('Todos los usuarios registrados en la plataforma') }}</p>
        </div>
        <p class="text-sm text-neutral-500">{{ trans_choice(':count usuario|:count usuarios', $this->usuarios->count(), ['count' => $this->usuarios->count()]) }}</p>
    </div>

    <div class="mb-5 flex flex-wrap items-center gap-3">
        <div class="relative">
            <flux:icon name="magnifying-glass" variant="micro" class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-neutral-400" />
            <input
                type="search"
                wire:model.live.debounce.400ms="busqueda"
                placeholder="{{ __('Buscar por nombre o correo') }}"
                class="w-64 rounded-lg border border-neutral-300 bg-white py-2 pr-3 pl-9 text-sm text-neutral-900"
            >
        </div>

        <select wire:model.live="estado" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
            <option value="todos">{{ __('Todos los estados') }}</option>
            <option value="activo">{{ __('Activo') }}</option>
            <option value="inactivo">{{ __('Inactivo') }}</option>
            <option value="suspendido">{{ __('Suspendido') }}</option>
        </select>

        <select wire:model.live="rol" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
            <option value="todos">{{ __('Todos los roles') }}</option>
            <option value="adoptante">{{ __('Adoptante') }}</option>
            <option value="fundacion">{{ __('Fundación') }}</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
        @if ($this->usuarios->isEmpty())
            <p class="p-6 text-center text-sm text-neutral-500">{{ __('No se encontraron usuarios con estos filtros.') }}</p>
        @else
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 text-xs tracking-widest text-neutral-500 uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('Nombre') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Correo') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Documento') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Registro') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Roles') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Estado') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach ($this->usuarios as $usuario)
                        <tr wire:key="usuario-{{ $usuario->id_usuario }}">
                            <td class="px-5 py-3 font-medium">{{ $usuario->nombres }} {{ $usuario->primer_apellido }}</td>
                            <td class="px-5 py-3 text-[#1f5c47]">{{ $usuario->correo }}</td>
                            <td class="px-5 py-3 text-neutral-600 uppercase">{{ $usuario->tipo_documento }} {{ $usuario->numero_documento }}</td>
                            <td class="px-5 py-3 text-neutral-600">{{ $usuario->fecha_registro->format('Y-m-d') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($usuario->roles_calculados as $rolUsuario)
                                        <span @class([
                                            'rounded-full px-2 py-0.5 text-xs font-semibold',
                                            'bg-purple-100 text-purple-700' => $rolUsuario === 'admin',
                                            'bg-amber-100 text-amber-700' => $rolUsuario === 'fundacion',
                                            'bg-[#dcece4] text-[#234a3a]' => $rolUsuario === 'adoptante',
                                        ])>
                                            {{ match ($rolUsuario) {
                                                'admin' => 'Admin',
                                                'fundacion' => __('Fundación'),
                                                default => __('Adoptante'),
                                            } }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-[#dcece4] text-[#234a3a]' => $usuario->estado === 'activo',
                                    'bg-neutral-100 text-neutral-600' => $usuario->estado === 'inactivo',
                                    'bg-red-100 text-red-700' => $usuario->estado === 'suspendido',
                                ])>
                                    {{ ucfirst($usuario->estado) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <flux:dropdown position="bottom" align="end">
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-vertical" square />
                                    <flux:menu>
                                        @if ($usuario->estado !== 'activo')
                                            <flux:menu.item as="button" type="button" wire:click="cambiarEstado({{ $usuario->id_usuario }}, 'activo')">
                                                {{ __('Activar') }}
                                            </flux:menu.item>
                                        @endif
                                        @if ($usuario->estado !== 'suspendido')
                                            <flux:menu.item as="button" type="button" wire:click="cambiarEstado({{ $usuario->id_usuario }}, 'suspendido')">
                                                {{ __('Suspender') }}
                                            </flux:menu.item>
                                        @endif
                                        @if ($usuario->estado !== 'inactivo')
                                            <flux:menu.item as="button" type="button" wire:click="cambiarEstado({{ $usuario->id_usuario }}, 'inactivo')">
                                                {{ __('Marcar inactivo') }}
                                            </flux:menu.item>
                                        @endif
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
