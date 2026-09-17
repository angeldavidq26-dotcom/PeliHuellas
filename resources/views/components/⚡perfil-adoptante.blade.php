<?php

use App\Models\PerfilAdoptante;
use App\Models\PerfilCuidado;
use App\Models\PerfilVivienda;
use App\Models\Usuario;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component {
    #[Url]
    public ?int $volver = null;

    public ?Usuario $usuario = null;

    public int $currentStep = 1;

    public int $maxStepReached = 1;

    // Paso 1 — documento
    public string $tipoDocumento = 'cc';

    public string $numeroDocumento = '';

    public string $nombres = '';

    public string $primerApellido = '';

    public string $telefono = '';

    // Paso 2 — sobre vos
    public string $ocupacion = '';

    public string $deseaAdoptar = 'indiferente';

    public bool $experienciaPrevia = false;

    public bool $decisionFamiliar = true;

    public bool $todosDeAcuerdo = true;

    public string $motivacion = '';

    // Paso 3 — vivienda
    public string $tipoInmueble = 'casa';

    public string $tenencia = 'propia';

    public ?int $areaM2 = null;

    public bool $tienePatio = false;

    public bool $areaCubierta = true;

    public string $lugarMascota = 'interior';

    public ?int $personasHogar = null;

    public string $conQuienVive = '';

    public bool $hayNinos = false;

    public bool $personaConAlergia = false;

    public string $detalleAlergia = '';

    public bool $todosAceptan = true;

    // Paso 4 — cuidados
    public ?int $horasSoloAlDia = null;

    public string $lugarPermanente = '';

    public string $dondeHaceNecesidades = '';

    public string $tipoAlimento = '';

    public bool $asumeCostoSalud = true;

    public bool $aceptaTratamiento = true;

    public bool $aceptaEsterilizacion = true;

    public bool $aceptaCirugias = true;

    public bool $tieneOtrasMascotas = false;

    public string $cualesMascotas = '';

    // Paso 5 — referencias
    /** @var array<int, array{nombre: string, parentesco: string, telefono: string, ocupacion: string}> */
    public array $referencias = [
        ['nombre' => '', 'parentesco' => '', 'telefono' => '', 'ocupacion' => ''],
    ];

    public function mount(): void
    {
        $this->usuario = Usuario::paraUser(auth()->user());

        $this->nombres = auth()->user()->nombres ?: auth()->user()->name;
        $this->primerApellido = auth()->user()->apellidos ?: '';
        $this->telefono = auth()->user()->telefono ?: '';

        if (! $this->usuario) {
            return;
        }

        $this->tipoDocumento = $this->usuario->tipo_documento;
        $this->numeroDocumento = $this->usuario->numero_documento;
        $this->nombres = $this->usuario->nombres;
        $this->primerApellido = $this->usuario->primer_apellido;
        $this->telefono = (string) $this->usuario->telefono;

        $perfil = $this->usuario->perfilAdoptante;

        if (! $perfil) {
            return;
        }

        $this->ocupacion = (string) $perfil->ocupacion;
        $this->deseaAdoptar = $perfil->desea_adoptar;
        $this->experienciaPrevia = $perfil->experiencia_previa;
        $this->decisionFamiliar = $perfil->decision_familiar;
        $this->todosDeAcuerdo = $perfil->todos_de_acuerdo;
        $this->motivacion = (string) $perfil->motivacion;

        if ($vivienda = $perfil->vivienda) {
            $this->tipoInmueble = $vivienda->tipo_inmueble;
            $this->tenencia = $vivienda->tenencia;
            $this->areaM2 = $vivienda->area_m2;
            $this->tienePatio = $vivienda->tiene_patio;
            $this->areaCubierta = $vivienda->area_cubierta;
            $this->lugarMascota = $vivienda->lugar_mascota;
            $this->personasHogar = $vivienda->personas_hogar;
            $this->conQuienVive = (string) $vivienda->con_quien_vive;
            $this->hayNinos = $vivienda->hay_ninos;
            $this->personaConAlergia = $vivienda->persona_con_alergia;
            $this->detalleAlergia = (string) $vivienda->detalle_alergia;
            $this->todosAceptan = $vivienda->todos_aceptan;
        }

        if ($cuidado = $perfil->cuidado) {
            $this->horasSoloAlDia = $cuidado->horas_solo_al_dia;
            $this->lugarPermanente = (string) $cuidado->lugar_permanente;
            $this->dondeHaceNecesidades = (string) $cuidado->donde_hace_necesidades;
            $this->tipoAlimento = (string) $cuidado->tipo_alimento;
            $this->asumeCostoSalud = $cuidado->asume_costo_salud;
            $this->aceptaTratamiento = $cuidado->acepta_tratamiento;
            $this->aceptaEsterilizacion = $cuidado->acepta_esterilizacion;
            $this->aceptaCirugias = $cuidado->acepta_cirugias;
            $this->tieneOtrasMascotas = $cuidado->tiene_otras_mascotas;
            $this->cualesMascotas = (string) $cuidado->cuales_mascotas;
        }

        if ($perfil->referencias->isNotEmpty()) {
            $this->referencias = $perfil->referencias->map(fn ($r) => [
                'nombre' => $r->nombre,
                'parentesco' => $r->parentesco,
                'telefono' => $r->telefono,
                'ocupacion' => (string) $r->ocupacion,
            ])->all();
        }
    }

    public function agregarReferencia(): void
    {
        if (count($this->referencias) < 3) {
            $this->referencias[] = ['nombre' => '', 'parentesco' => '', 'telefono' => '', 'ocupacion' => ''];
        }
    }

    public function quitarReferencia(int $index): void
    {
        if (count($this->referencias) > 1) {
            unset($this->referencias[$index]);
            $this->referencias = array_values($this->referencias);
        }
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

    public function guardar(): void
    {
        $this->validate(array_merge(
            $this->rulesForStep(1),
            $this->rulesForStep(2),
            $this->rulesForStep(3),
            $this->rulesForStep(4),
            $this->rulesForStep(5),
        ));

        DB::transaction(function (): void {
            $datosUsuario = [
                'tipo_documento' => $this->tipoDocumento,
                'numero_documento' => $this->numeroDocumento,
                'nombres' => $this->nombres,
                'primer_apellido' => $this->primerApellido,
                'telefono' => $this->telefono,
            ];

            if ($this->usuario) {
                $this->usuario->update($datosUsuario);
            } else {
                $this->usuario = Usuario::create([
                    ...$datosUsuario,
                    'id_user' => auth()->id(),
                    'correo' => auth()->user()->email,
                    'contrasena_hash' => auth()->user()->password,
                    'fecha_registro' => now(),
                    'estado' => 'activo',
                ]);
            }

            $perfil = PerfilAdoptante::updateOrCreate(
                ['id_usuario' => $this->usuario->id_usuario],
                [
                    'ocupacion' => $this->ocupacion ?: null,
                    'desea_adoptar' => $this->deseaAdoptar,
                    'experiencia_previa' => $this->experienciaPrevia,
                    'decision_familiar' => $this->decisionFamiliar,
                    'todos_de_acuerdo' => $this->todosDeAcuerdo,
                    'motivacion' => $this->motivacion ?: null,
                    'completo' => true,
                    'fecha_diligenciamiento' => $this->usuario->perfilAdoptante?->fecha_diligenciamiento ?? now(),
                    'fecha_actualizacion' => now(),
                ]
            );

            PerfilVivienda::updateOrCreate(
                ['id_usuario' => $perfil->id_usuario],
                [
                    'tipo_inmueble' => $this->tipoInmueble,
                    'tenencia' => $this->tenencia,
                    'area_m2' => $this->areaM2,
                    'tiene_patio' => $this->tienePatio,
                    'area_cubierta' => $this->areaCubierta,
                    'lugar_mascota' => $this->lugarMascota,
                    'personas_hogar' => $this->personasHogar,
                    'con_quien_vive' => $this->conQuienVive ?: null,
                    'hay_ninos' => $this->hayNinos,
                    'persona_con_alergia' => $this->personaConAlergia,
                    'detalle_alergia' => $this->personaConAlergia ? ($this->detalleAlergia ?: null) : null,
                    'todos_aceptan' => $this->todosAceptan,
                ]
            );

            PerfilCuidado::updateOrCreate(
                ['id_usuario' => $perfil->id_usuario],
                [
                    'horas_solo_al_dia' => $this->horasSoloAlDia,
                    'lugar_permanente' => $this->lugarPermanente ?: null,
                    'donde_hace_necesidades' => $this->dondeHaceNecesidades ?: null,
                    'tipo_alimento' => $this->tipoAlimento ?: null,
                    'asume_costo_salud' => $this->asumeCostoSalud,
                    'acepta_tratamiento' => $this->aceptaTratamiento,
                    'acepta_esterilizacion' => $this->aceptaEsterilizacion,
                    'acepta_cirugias' => $this->aceptaCirugias,
                    'tiene_otras_mascotas' => $this->tieneOtrasMascotas,
                    'cuales_mascotas' => $this->tieneOtrasMascotas ? ($this->cualesMascotas ?: null) : null,
                ]
            );

            $perfil->referencias()->delete();
            $perfil->referencias()->createMany(
                collect($this->referencias)
                    ->filter(fn ($r) => trim($r['nombre']) !== '')
                    ->values()
                    ->all()
            );
        });

        Flux::toast(variant: 'success', text: __('Tu perfil de adoptante quedó completo.'));

        $this->redirectRoute(
            $this->volver ? 'mascotas.show' : 'mascotas',
            $this->volver ? ['mascota' => $this->volver] : [],
            navigate: true,
        );
    }

    protected function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'tipoDocumento' => ['required', Rule::in(['cc', 'ce', 'ti', 'pasaporte'])],
                'numeroDocumento' => [
                    'required', 'string', 'max:30',
                    Rule::unique('usuario', 'numero_documento')
                        ->where('tipo_documento', $this->tipoDocumento)
                        ->ignore($this->usuario?->id_usuario, 'id_usuario'),
                ],
                'nombres' => ['required', 'string', 'max:80'],
                'primerApellido' => ['required', 'string', 'max:80'],
                'telefono' => ['required', 'string', 'max:25'],
            ],
            2 => [
                'ocupacion' => ['nullable', 'string', 'max:120'],
                'deseaAdoptar' => ['required', Rule::in(['perro', 'gato', 'ambos', 'indiferente'])],
                'motivacion' => ['nullable', 'string', 'max:1000'],
            ],
            3 => [
                'tipoInmueble' => ['required', Rule::in(['casa', 'apartamento', 'finca', 'otro'])],
                'tenencia' => ['required', Rule::in(['propia', 'arrendada', 'familiar'])],
                'areaM2' => ['nullable', 'integer', 'min:1', 'max:5000'],
                'lugarMascota' => ['required', Rule::in(['interior', 'patio', 'ambos'])],
                'personasHogar' => ['nullable', 'integer', 'min:1', 'max:50'],
                'conQuienVive' => ['nullable', 'string', 'max:200'],
                'detalleAlergia' => ['nullable', 'string', 'max:300'],
            ],
            4 => [
                'horasSoloAlDia' => ['nullable', 'integer', 'min:0', 'max:24'],
                'lugarPermanente' => ['nullable', 'string', 'max:200'],
                'dondeHaceNecesidades' => ['nullable', 'string', 'max:200'],
                'tipoAlimento' => ['nullable', 'string', 'max:120'],
                'cualesMascotas' => ['nullable', 'string', 'max:200'],
            ],
            5 => [
                'referencias' => ['array', 'min:1'],
                'referencias.*.nombre' => ['required', 'string', 'max:160'],
                'referencias.*.parentesco' => ['required', 'string', 'max:80'],
                'referencias.*.telefono' => ['required', 'string', 'max:25'],
                'referencias.*.ocupacion' => ['nullable', 'string', 'max:120'],
            ],
            default => [],
        };
    }
}; ?>

<div>
    @php
        $pasos = [
            1 => __('Documento'),
            2 => __('Sobre vos'),
            3 => __('Vivienda'),
            4 => __('Cuidados'),
            5 => __('Referencias'),
        ];
    @endphp

    <h1 class="pf-serif mb-2 text-3xl font-bold">{{ __('Completá tu perfil de adoptante') }}</h1>
    <p class="mb-6 text-neutral-500">{{ __('Esta información la ven las fundaciones cuando solicitás una adopción.') }}</p>

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
        {{-- Paso 1: Documento --}}
        @if ($currentStep === 1)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <flux:label>{{ __('Tipo de documento') }}</flux:label>
                    <select wire:model="tipoDocumento" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                        <option value="cc">{{ __('Cédula de ciudadanía') }}</option>
                        <option value="ce">{{ __('Cédula de extranjería') }}</option>
                        <option value="ti">{{ __('Tarjeta de identidad') }}</option>
                        <option value="pasaporte">{{ __('Pasaporte') }}</option>
                    </select>
                </div>

                <flux:input wire:model="numeroDocumento" :label="__('Número de documento')" placeholder="1000123456" />
                <flux:input wire:model="nombres" :label="__('Nombres')" />
                <flux:input wire:model="primerApellido" :label="__('Apellido')" />
                <flux:input wire:model="telefono" :label="__('Teléfono')" type="tel" placeholder="310 555 0000" />
            </div>
        @endif

        {{-- Paso 2: Sobre vos --}}
        @if ($currentStep === 2)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <flux:input wire:model="ocupacion" :label="__('Ocupación')" :description="__('Opcional')" />

                <div>
                    <flux:label>{{ __('¿Qué te gustaría adoptar?') }}</flux:label>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @foreach (['perro' => __('Perro'), 'gato' => __('Gato'), 'ambos' => __('Ambos'), 'indiferente' => __('Indiferente')] as $value => $label)
                            <label class="relative">
                                <input type="radio" wire:model="deseaAdoptar" value="{{ $value }}" class="peer sr-only">
                                <span class="block cursor-pointer rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm peer-checked:border-[#1f5c47] peer-checked:bg-[#1f5c47] peer-checked:text-white">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <flux:checkbox wire:model="experienciaPrevia" :label="__('Tuve mascotas antes')" />
                <flux:checkbox wire:model="decisionFamiliar" :label="__('La decisión la tomó toda la familia')" />
                <flux:checkbox wire:model="todosDeAcuerdo" :label="__('Todos en casa están de acuerdo')" />

                <div class="sm:col-span-2">
                    <flux:textarea wire:model="motivacion" :label="__('¿Por qué querés adoptar?')" :description="__('Opcional')" rows="4" />
                </div>
            </div>
        @endif

        {{-- Paso 3: Vivienda --}}
        @if ($currentStep === 3)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <flux:label>{{ __('Tipo de inmueble') }}</flux:label>
                    <select wire:model="tipoInmueble" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                        <option value="casa">{{ __('Casa') }}</option>
                        <option value="apartamento">{{ __('Apartamento') }}</option>
                        <option value="finca">{{ __('Finca') }}</option>
                        <option value="otro">{{ __('Otro') }}</option>
                    </select>
                </div>

                <div>
                    <flux:label>{{ __('Tenencia') }}</flux:label>
                    <select wire:model="tenencia" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                        <option value="propia">{{ __('Propia') }}</option>
                        <option value="arrendada">{{ __('Arrendada') }}</option>
                        <option value="familiar">{{ __('Familiar') }}</option>
                    </select>
                </div>

                <flux:input wire:model="areaM2" type="number" min="1" :label="__('Área (m²)')" :description="__('Opcional')" />

                <div>
                    <flux:label>{{ __('¿Dónde estaría la mascota?') }}</flux:label>
                    <select wire:model="lugarMascota" class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900">
                        <option value="interior">{{ __('Interior') }}</option>
                        <option value="patio">{{ __('Patio') }}</option>
                        <option value="ambos">{{ __('Ambos') }}</option>
                    </select>
                </div>

                <flux:checkbox wire:model="tienePatio" :label="__('Tiene patio')" />
                <flux:checkbox wire:model="areaCubierta" :label="__('El área está cubierta/cercada')" />

                <flux:input wire:model="personasHogar" type="number" min="1" :label="__('Personas en el hogar')" :description="__('Opcional')" />
                <flux:input wire:model="conQuienVive" :label="__('¿Con quién vivís?')" :description="__('Opcional')" />

                <flux:checkbox wire:model="hayNinos" :label="__('Hay niños en el hogar')" />
                <flux:checkbox wire:model.live="personaConAlergia" :label="__('Alguien tiene alergia a animales')" />

                @if ($personaConAlergia)
                    <div class="sm:col-span-2">
                        <flux:input wire:model="detalleAlergia" :label="__('Contanos más sobre la alergia')" />
                    </div>
                @endif

                <flux:checkbox wire:model="todosAceptan" :label="__('Todos en el hogar aceptan tener una mascota')" />
            </div>
        @endif

        {{-- Paso 4: Cuidados --}}
        @if ($currentStep === 4)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <flux:input wire:model="horasSoloAlDia" type="number" min="0" max="24" :label="__('Horas sola/o al día')" :description="__('Opcional')" />
                <flux:input wire:model="tipoAlimento" :label="__('Tipo de alimento que le darías')" :description="__('Opcional')" />
                <flux:input wire:model="lugarPermanente" :label="__('Lugar donde dormiría/viviría')" :description="__('Opcional')" />
                <flux:input wire:model="dondeHaceNecesidades" :label="__('Dónde haría sus necesidades')" :description="__('Opcional')" />

                <flux:checkbox wire:model="asumeCostoSalud" :label="__('Asumirías los costos de salud')" />
                <flux:checkbox wire:model="aceptaTratamiento" :label="__('Aceptás dar tratamientos médicos si hacen falta')" />
                <flux:checkbox wire:model="aceptaEsterilizacion" :label="__('Aceptás esterilizar')" />
                <flux:checkbox wire:model="aceptaCirugias" :label="__('Aceptás cirugías si son necesarias')" />

                <flux:checkbox wire:model.live="tieneOtrasMascotas" :label="__('Ya tenés otras mascotas')" />
                @if ($tieneOtrasMascotas)
                    <flux:input wire:model="cualesMascotas" :label="__('¿Cuáles?')" />
                @endif
            </div>
        @endif

        {{-- Paso 5: Referencias --}}
        @if ($currentStep === 5)
            <div class="space-y-6">
                <p class="text-sm text-neutral-500">{{ __('Al menos una persona que pueda confirmar que sos una buena opción para adoptar.') }}</p>

                @foreach ($referencias as $index => $referencia)
                    <div class="rounded-lg border border-neutral-200 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-sm font-semibold">{{ __('Referencia :n', ['n' => $index + 1]) }}</p>
                            @if (count($referencias) > 1)
                                <button type="button" wire:click="quitarReferencia({{ $index }})" class="text-xs text-red-600">{{ __('Quitar') }}</button>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <flux:input wire:model="referencias.{{ $index }}.nombre" :label="__('Nombre')" />
                            <flux:input wire:model="referencias.{{ $index }}.parentesco" :label="__('Parentesco / relación')" placeholder="{{ __('Hermana, amigo, vecino…') }}" />
                            <flux:input wire:model="referencias.{{ $index }}.telefono" type="tel" :label="__('Teléfono')" />
                            <flux:input wire:model="referencias.{{ $index }}.ocupacion" :label="__('Ocupación')" :description="__('Opcional')" />
                        </div>
                    </div>
                @endforeach

                @if (count($referencias) < 3)
                    <flux:button type="button" wire:click="agregarReferencia" variant="filled" size="sm">
                        {{ __('+ Agregar otra referencia') }}
                    </flux:button>
                @endif
            </div>
        @endif
    </div>

    <div class="mt-6 flex items-center justify-between">
        @if ($currentStep === 1)
            <flux:button :href="$volver ? route('mascotas.show', $volver) : route('mascotas')" variant="filled" wire:navigate>{{ __('Cancelar') }}</flux:button>
        @else
            <flux:button wire:click="back" variant="filled">{{ __('Atrás') }}</flux:button>
        @endif

        @if ($currentStep < 5)
            <flux:button wire:click="next" variant="primary">{{ __('Continuar') }}</flux:button>
        @else
            <flux:button wire:click="guardar" variant="primary">{{ __('Guardar perfil') }}</flux:button>
        @endif
    </div>
</div>
