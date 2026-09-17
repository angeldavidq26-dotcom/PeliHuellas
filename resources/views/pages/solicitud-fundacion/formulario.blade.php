<x-layouts::auth :title="__('Registrar mi fundación')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Registrá tu fundación')"
            :description="__('Contanos sobre tu organización. Nuestro equipo revisa cada solicitud y te contacta para confirmar tu ingreso.')"
        />

        <form method="POST" action="{{ route('solicitud-fundacion.enviar') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="nombre"
                :label="__('Nombre de la fundación')"
                :value="old('nombre')"
                required
                autofocus
                placeholder="Huellas Felices"
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    name="nit"
                    :label="__('NIT')"
                    :value="old('nit')"
                    required
                    placeholder="900.123.456-7"
                />

                <flux:input
                    name="capacidad"
                    :label="__('Capacidad aprox.')"
                    type="number"
                    min="1"
                    :value="old('capacidad')"
                    :description="__('Animales')"
                    placeholder="20"
                />
            </div>

            <flux:input
                name="contacto_nombre"
                :label="__('Persona de contacto')"
                :value="old('contacto_nombre')"
                required
                placeholder="María García"
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    name="correo"
                    :label="__('Correo de contacto')"
                    :value="old('correo')"
                    type="email"
                    required
                    placeholder="contacto@fundacion.org"
                />

                <flux:input
                    name="telefono"
                    :label="__('Teléfono')"
                    :value="old('telefono')"
                    type="tel"
                    required
                    placeholder="310 555 0000"
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    name="ciudad"
                    :label="__('Ciudad')"
                    :value="old('ciudad')"
                    required
                    placeholder="Bogotá"
                />

                <flux:input
                    name="direccion"
                    :label="__('Dirección')"
                    :value="old('direccion')"
                    required
                    placeholder="Calle 123 # 45-67"
                />
            </div>

            <flux:textarea
                name="descripcion"
                :label="__('Contanos sobre tu fundación')"
                :description="__('Cuántos animales atienden, hace cuánto trabajan, qué las hace distintas.')"
                rows="4"
                required
                placeholder="{{ __('Ej: Somos una fundación en Bogotá que lleva 5 años rescatando y rehabilitando perros y gatos en situación de calle...') }}"
            >{{ old('descripcion') }}</flux:textarea>

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Enviar solicitud') }}
            </flux:button>
        </form>

        <div class="rounded-lg bg-[#dcece4] p-4 text-sm text-[#234a3a] dark:bg-emerald-950 dark:text-emerald-200">
            {{ __('Revisamos manualmente cada fundación antes de aprobarla, para garantizar la seguridad de las adopciones.') }}
        </div>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('¿Ya tenés una cuenta?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Ingresar') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
