<x-layouts::auth :title="__('Crear cuenta')">
    {{--
        Flux solo trae tamaños default/sm/xs para los campos, así que se escalan aquí.
        Ojo con los selectores: data-flux-input va en el div contenedor (no en el <input>)
        y las etiquetas se renderizan como <ui-label data-flux-label>.
    --}}
    <style>
        .ph-registro__titulo {
            margin-top: clamp(10px, 1.6vh, 22px);
            font-size: clamp(32px, 5vh, 52px);
            font-weight: 700;
            line-height: 1.05;
            color: var(--ph-tinta);
        }

        .ph-registro__subtitulo {
            margin-top: 4px;
            font-size: clamp(14px, 1.9vh, 18px);
            line-height: 1.4;
            color: var(--ph-gris);
        }

        .ph-registro__form {
            display: flex;
            flex-direction: column;
            gap: clamp(8px, 1.4vh, 16px);
            margin-top: clamp(12px, 2.2vh, 28px);
        }

        .ph-registro__fila {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: clamp(10px, 1.2vw, 18px);
        }

        /* Flux renderiza ui-field como grid con gap propio; el espaciado lo da el margen de la etiqueta. */
        .ph-registro [data-flux-field] {
            gap: 0 !important;
        }

        .ph-registro [data-flux-label] {
            display: block;
            margin-bottom: clamp(3px, 0.6vh, 8px) !important;
            font-size: clamp(13px, 1.75vh, 16px);
            font-weight: 600;
            line-height: 1.25;
            color: #1D2B28;
        }

        .ph-registro [data-flux-input] input {
            height: clamp(42px, 5.9vh, 56px);
            border: 1px solid #DCE3E1;
            border-radius: 10px;
            background: #fff;
            box-shadow: none;
            font-size: clamp(14px, 1.8vh, 16.5px);
            color: #1F2A27;
        }

        .ph-registro [data-flux-input] input:focus {
            border-color: var(--ph-verde);
            box-shadow: 0 0 0 3px rgba(0, 107, 91, 0.14);
            outline: none;
        }

        .ph-registro [data-flux-input] input::placeholder {
            color: #9AA8A4;
        }

        .ph-registro [data-flux-input] [data-flux-icon] {
            color: var(--ph-gris);
        }

        .ph-registro__boton {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            height: clamp(44px, 6vh, 58px);
            margin-top: clamp(0px, 0.4vh, 6px);
            border-radius: 28px;
            background: var(--ph-verde);
            box-shadow: 0 14px 26px -16px rgba(0, 85, 72, 0.9);
            color: #fff;
            font-size: clamp(15px, 2vh, 18px);
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .ph-registro__boton:hover {
            background: var(--ph-verde-oscuro);
        }

        .ph-registro__boton:active {
            transform: translateY(1px);
        }

        .ph-registro__boton svg {
            width: 20px;
            height: 20px;
        }

        .ph-registro__terminos {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: clamp(10px, 1.6vh, 18px);
            padding: clamp(9px, 1.3vh, 16px) clamp(14px, 1.4vw, 22px);
            border-radius: 16px;
            background: var(--ph-verde-suave);
            font-size: clamp(13px, 1.65vh, 15px);
            line-height: 1.45;
            color: var(--ph-tinta);
        }

        .ph-registro__terminos svg {
            flex: none;
            width: 22px;
            height: 22px;
            color: var(--ph-verde);
        }

        .ph-registro__terminos a,
        .ph-registro__login a {
            font-weight: 600;
            color: var(--ph-verde-oscuro);
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .ph-registro__login {
            margin-top: clamp(10px, 1.6vh, 18px);
            text-align: center;
            font-size: clamp(13.5px, 1.7vh, 15.5px);
            color: var(--ph-gris);
        }

        @media (max-width: 520px) {
            .ph-registro__fila {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="ph-registro">
        <h1 class="pf-serif ph-registro__titulo">{{ __('Crear cuenta') }}</h1>
        <p class="ph-registro__subtitulo">{{ __('Únete a nuestra comunidad y haz la diferencia.') }}</p>

        <!-- Session Status -->
        <x-auth-session-status class="mt-4 text-center" :status="session('status')" />

        @if ($teamInvitation)
            <x-team-invitation-alert :invitation="$teamInvitation" :action="__('Register')" class="mt-4" />
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="ph-registro__form">
            @csrf

            <!-- Nombre / Apellidos -->
            <div class="ph-registro__fila">
                <flux:input
                    name="nombres"
                    :label="__('Nombre')"
                    :value="old('nombres')"
                    type="text"
                    icon="user"
                    required
                    autofocus
                    autocomplete="given-name"
                    :placeholder="__('Tu nombre')"
                />

                <flux:input
                    name="apellidos"
                    :label="__('Apellidos')"
                    :value="old('apellidos')"
                    type="text"
                    icon="user"
                    required
                    autocomplete="family-name"
                    :placeholder="__('Tus apellidos')"
                />
            </div>

            <!-- Correo electrónico -->
            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                icon="envelope"
                required
                autocomplete="email"
                placeholder="ejemplo@correo.com"
            />

            <!-- Teléfono -->
            <flux:input
                name="telefono"
                :label="__('Teléfono')"
                :value="old('telefono')"
                type="tel"
                icon="phone"
                autocomplete="tel"
                placeholder="+57 300 123 4567"
            />

            <!-- Contraseña -->
            <flux:input
                name="password"
                :label="__('Contraseña')"
                type="password"
                icon="lock-closed"
                required
                autocomplete="new-password"
                :placeholder="__('Mínimo 8 caracteres')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirmar contraseña -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                icon="lock-closed"
                required
                autocomplete="new-password"
                :placeholder="__('Repite tu contraseña')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            {{-- El consentimiento se da al enviar el formulario; el aviso queda a la vista debajo del botón. --}}
            <input type="hidden" name="terms" value="1">

            <button type="submit" class="ph-registro__boton" data-test="register-user-button">
                <flux:icon.user variant="mini" />
                {{ __('Crear cuenta') }}
                <flux:icon.arrow-right variant="mini" />
            </button>
        </form>

        <div class="ph-registro__terminos">
            <flux:icon.shield-check variant="solid" />
            <p>
                {{ __('Al crear una cuenta, aceptas nuestros') }}
                <a href="#">{{ __('Términos y Condiciones') }}</a><br>
                {{ __('y nuestra') }}
                <a href="#">{{ __('Política de Privacidad') }}</a>.
            </p>
        </div>

        <p class="ph-registro__login">
            {{ __('¿Ya tienes una cuenta?') }}
            <a
                href="{{ $teamInvitation ? route('login', ['invitation' => $teamInvitation['code']]) : route('login') }}"
                data-test="team-invitation-login-link"
                wire:navigate
            >{{ __('Inicia sesión') }}</a>
        </p>
    </div>
</x-layouts::auth>
