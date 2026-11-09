<x-layouts::auth :title="__('Crear cuenta')">
    {{--
        Flux solo trae tamaños default/sm/xs para los campos, así que se escalan aquí.
        Ojo con los selectores: data-flux-input va en el div contenedor (no en el <input>)
        y las etiquetas se renderizan como <ui-label data-flux-label>.

        Escala fluida: cada tamaño usa min(vw, vh) para crecer en pantallas grandes
        sin desbordar en portátiles de poca altura, y se limita con clamp().
    --}}
    <style>
        .ph-registro {
            --r-titulo: clamp(34px, min(3.4vw, 6vh), 66px);
            --r-subtitulo: clamp(15px, min(1.25vw, 2.2vh), 22px);
            --r-label: clamp(13.5px, min(1.05vw, 1.95vh), 19px);
            --r-texto: clamp(14.5px, min(1.1vw, 2vh), 19px);
            --r-input-h: clamp(46px, min(3.4vw, 6.4vh), 70px);
            --r-boton-h: clamp(48px, min(3.6vw, 6.8vh), 74px);
            --r-boton-fs: clamp(16px, min(1.15vw, 2.15vh), 21px);
            --r-small: clamp(13.5px, min(1vw, 1.85vh), 18px);
            --r-gap: clamp(10px, min(1vw, 1.9vh), 24px);
            --r-radio: clamp(10px, .8vw, 16px);
            --r-icono: clamp(18px, 1.3vw, 24px);

            width: 100%;
        }

        .ph-registro__titulo {
            margin-top: clamp(10px, 1.8vh, 26px);
            font-size: var(--r-titulo);
            font-weight: 700;
            line-height: 1.05;
            color: var(--ph-tinta);
        }

        .ph-registro__subtitulo {
            margin-top: clamp(4px, .6vh, 10px);
            font-size: var(--r-subtitulo);
            line-height: 1.4;
            color: var(--ph-gris);
        }

        .ph-registro__form {
            display: flex;
            flex-direction: column;
            gap: var(--r-gap);
            margin-top: clamp(14px, 2.6vh, 36px);
        }

        .ph-registro__fila {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: var(--r-gap);
        }

        /* Flux renderiza ui-field como grid con gap propio; el espaciado lo da el margen de la etiqueta. */
        .ph-registro [data-flux-field] {
            gap: 0 !important;
        }

        .ph-registro [data-flux-label] {
            display: block;
            margin-bottom: clamp(4px, .7vh, 10px) !important;
            font-size: var(--r-label);
            font-weight: 600;
            line-height: 1.25;
            color: #1D2B28;
        }

        .ph-registro [data-flux-input] input {
            height: var(--r-input-h);
            border: 1px solid #DCE3E1;
            border-radius: var(--r-radio);
            background: #fff;
            box-shadow: none;
            font-size: var(--r-texto);
            color: #1F2A27;
        }

        .ph-registro [data-flux-input] input:focus {
            border-color: var(--ph-verde);
            box-shadow: 0 0 0 3px rgba(0, 107, 91, 0.14);
            outline: none;
        }

        .ph-registro [data-flux-input] input::placeholder {
            color: #9AA8A4;
            font-size: var(--r-texto);
        }

        .ph-registro [data-flux-input] [data-flux-icon] {
            width: var(--r-icono);
            height: var(--r-icono);
            color: var(--ph-gris);
        }

        /* Boton del ojo (viewable): area comoda y icono centrado.
           No se toca "display" de los SVG para que Flux alterne ojo / ojo tachado. */
        .ph-registro [data-flux-input] button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            min-height: 40px;
            line-height: 0;
        }

        .ph-registro__boton {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            height: var(--r-boton-h);
            margin-top: clamp(0px, .6vh, 8px);
            border-radius: 999px;
            background: var(--ph-verde);
            box-shadow: 0 14px 26px -16px rgba(0, 85, 72, 0.9);
            color: #fff;
            font-size: var(--r-boton-fs);
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
            flex: none;
            width: clamp(20px, 1.4vw, 26px);
            height: clamp(20px, 1.4vw, 26px);
        }

        .ph-registro__terminos {
            display: flex;
            align-items: center;
            gap: clamp(12px, 1vw, 20px);
            margin-top: clamp(12px, 2vh, 26px);
            padding: clamp(11px, 1.6vh, 22px) clamp(14px, 1.5vw, 28px);
            border-radius: clamp(14px, 1.1vw, 22px);
            background: var(--ph-verde-suave);
            font-size: var(--r-small);
            line-height: 1.45;
            color: var(--ph-tinta);
        }

        .ph-registro__terminos svg {
            flex: none;
            width: clamp(22px, 1.6vw, 32px);
            height: clamp(22px, 1.6vw, 32px);
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
            margin-top: clamp(12px, 2vh, 26px);
            text-align: center;
            font-size: var(--r-small);
            color: var(--ph-gris);
        }

        /* Tablet: un poco mas de aire y controles comodos al tacto */
        @media (max-width: 900px) {
            .ph-registro {
                --r-titulo: clamp(34px, 6vw, 46px);
                --r-subtitulo: 16px;
                --r-label: 15px;
                --r-texto: 16px;
                --r-small: 14.5px;
                --r-input-h: 52px;
                --r-boton-h: 56px;
                --r-boton-fs: 17px;
                --r-gap: 16px;
                --r-icono: 20px;
            }
        }

        /* Movil: una columna, nombre y apellidos apilados */
        @media (max-width: 520px) {
            .ph-registro__fila {
                grid-template-columns: 1fr;
            }

            .ph-registro {
                --r-titulo: 34px;
                --r-gap: 14px;
            }

            .ph-registro__terminos {
                align-items: flex-start;
            }

            .ph-registro__terminos br {
                display: none;
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