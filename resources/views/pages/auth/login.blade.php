<x-layouts::auth.split2 :title="__('Iniciar sesión')">

    <div class="flex flex-col gap-6">

        {{-- Encabezado --}}
        <x-auth-header
            :title="__('Bienvenido de nuevo')"
            :description="__('Ingresá con tu cuenta de PeliHuellas.')"
        />

        {{-- Mensaje de sesión --}}
        <x-auth-session-status
            class="text-center"
            :status="session('status')"
        />

        {{-- Invitación a equipo --}}
        @if ($teamInvitation)
            <x-team-invitation-alert
                :invitation="$teamInvitation"
                :action="__('Log in')"
            />
        @endif

        {{-- Acceso mediante Passkey --}}
        <x-passkey-verify />

        {{-- Formulario de Login --}}
        <form
            method="POST"
            action="{{ route('login.store') }}"
            class="flex flex-col gap-6"
        >
            @csrf

            {{-- Correo electrónico --}}
            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="tu@correo.com"
            />

            {{-- Contraseña --}}
            <div class="relative">

                <flux:input
                    name="password"
                    :label="__('Contraseña')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Tu contraseña')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link
                        class="absolute top-0 text-sm end-0"
                        :href="route('password.request')"
                        wire:navigate
                    >
                        {{ __('Olvidé mi contraseña') }}
                    </flux:link>
                @endif

            </div>

            {{-- Recordarme --}}
            <flux:checkbox
                name="remember"
                :label="__('Recordarme')"
                :checked="old('remember')"
            />

            {{-- Botón de ingreso --}}
            <flux:button
                variant="primary"
                type="submit"
                class="w-full"
                data-test="login-button"
            >
                {{ __('Ingresar') }}
            </flux:button>

        </form>

        {{-- Información de seguridad --}}
        <div
            class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3"
        >

            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700"
            >
                <flux:icon.shield-check class="h-5 w-5" />
            </div>

            <div>
                <p class="text-sm font-semibold text-emerald-950">
                    {{ __('Tu información está protegida') }}
                </p>

                <p class="mt-0.5 text-xs leading-5 text-emerald-800/70">
                    {{ __('Inicia sesión de forma segura para continuar en PeliHuellas.') }}
                </p>
            </div>

        </div>

        {{-- Crear cuenta --}}
        <div
            class="text-center text-sm text-zinc-600 dark:text-zinc-400"
        >

            <span>
                {{ __('¿No tenés cuenta?') }}
            </span>

            <flux:link
                :href="$teamInvitation
                    ? route('register', ['invitation' => $teamInvitation['code']])
                    : route('register')"
                data-test="register-link"
                wire:navigate
            >
                {{ __('Crear cuenta') }}
            </flux:link>

        </div>

    </div>

</x-layouts::auth.split2>