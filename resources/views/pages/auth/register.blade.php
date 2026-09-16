<x-layouts::auth :title="__('Crear cuenta')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Crear cuenta')" :description="__('Únete a PeliHuellas. Es gratis.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        @if ($teamInvitation)
            <x-team-invitation-alert :invitation="$teamInvitation" :action="__('Register')" />
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Nombres / Apellidos -->
            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    name="nombres"
                    :label="__('Nombres')"
                    :value="old('nombres')"
                    type="text"
                    required
                    autofocus
                    autocomplete="given-name"
                    placeholder="María"
                />

                <flux:input
                    name="apellidos"
                    :label="__('Apellidos')"
                    :value="old('apellidos')"
                    type="text"
                    required
                    autocomplete="family-name"
                    placeholder="García"
                />
            </div>

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="tu@correo.com"
            />

            <!-- Phone -->
            <flux:input
                name="telefono"
                :label="__('Teléfono')"
                :value="old('telefono')"
                type="tel"
                autocomplete="tel"
                placeholder="310 555 0000"
                :description="__('Opcional — lo usan las fundaciones para contactarte.')"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Mínimo 8 caracteres')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Repetí la contraseña')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <flux:checkbox
                name="terms"
                required
                :label="__('Acepto los Términos de uso y la Política de privacidad.')"
            />

            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                {{ __('Crear cuenta') }}
            </flux:button>
        </form>

        <div class="rounded-lg bg-[#dcece4] p-4 text-sm text-[#234a3a] dark:bg-emerald-950 dark:text-emerald-200">
            {{ __('Con tu cuenta ya podés explorar animales en adopción. Para solicitar una adopción te pediremos algunos datos más.') }}
        </div>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('¿Ya tenés cuenta?') }}</span>
            <flux:link
                :href="$teamInvitation ? route('login', ['invitation' => $teamInvitation['code']]) : route('login')"
                data-test="team-invitation-login-link"
                wire:navigate
            >
                {{ __('Ingresar') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
