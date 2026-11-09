<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    <style>

        .ph-login {
            --ph-verde: #006B5B;
            --ph-verde-oscuro: #004F44;
            --ph-verde-claro: #A8E6A3;
            --ph-verde-suave: #E8F5F0;
            --ph-tinta: #0F3B33;
            --ph-gris: #687873;

            /* Escala tipografica fluida del formulario */
            --ph-fs-body: clamp(15px, 1.05vw, 21px);
            --ph-fs-small: clamp(13.5px, .9vw, 18px);
            --ph-control-h: clamp(54px, 3.5vw, 74px);
            --ph-radius: clamp(12px, .9vw, 18px);
            --ph-eye-size: clamp(40px, 2.8vw, 54px);
        }

        .ph-login *,
        .ph-login *::before,
        .ph-login *::after {
            box-sizing: border-box;
        }

        /* =========================================================
           CONTENEDOR PRINCIPAL
        ========================================================= */

        .ph-login__layout {
            min-height: 100dvh;
            display: grid;
            grid-template-columns: 55% 45%;
            overflow: hidden;
        }

        /* =========================================================
           COLUMNA IZQUIERDA
        ========================================================= */

        .ph-login__visual {
            position: relative;
            overflow: hidden;
            background: #004F44;
        }

        .ph-login__background {
            position: absolute;
            inset: -30px;
            background-image: url('{{ asset('images/aW5pY2lvcGVycm9nYXRv.jpg') }}');
            background-size: cover;
            background-position: center;
            filter: blur(12px);
            transform: scale(1.08);
        }

        .ph-login__image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* La fotografia mantiene el protagonismo */
        .ph-login__overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(
                    180deg,
                    rgba(0, 45, 39, 0.35) 0%,
                    rgba(0, 63, 53, 0.55) 45%,
                    rgba(0, 45, 39, 0.92) 100%
                );
        }

        .ph-login__content {
            position: relative;
            z-index: 2;

            height: 100%;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;

            padding:
                clamp(28px, 3.6vh, 60px)
                clamp(30px, 4vw, 100px)
                clamp(32px, 5vh, 80px);

            color: white;
        }

        /* =========================================================
           NAVEGACION
        ========================================================= */

        .ph-login__back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;

            width: fit-content;

            padding: clamp(11px, .8vw, 18px) clamp(18px, 1.3vw, 30px);

            border: 1px solid rgba(255,255,255,.28);
            border-radius: clamp(14px, 1vw, 22px);

            background: rgba(0, 60, 52, .45);
            backdrop-filter: blur(10px);

            color: white;

            font-size: clamp(15px, 1.05vw, 21px);
            font-weight: 600;
            line-height: 1;

            transition:
                background .2s ease,
                transform .2s ease;
        }

        .ph-login__back svg {
            flex: none;
            width: 1.25em;
            height: 1.25em;
            display: block;
        }

        .ph-login__back:hover {
            background: rgba(0, 60, 52, .72);
            transform: translateX(-3px);
        }

        /* =========================================================
           MENSAJE PRINCIPAL
        ========================================================= */

        .ph-login__hero {
            width: 100%;
            max-width: clamp(560px, 52vw, 1100px);
            margin-top: auto;
            margin-bottom: auto;
            padding-top: 4vh;
        }

        .ph-login__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;

            margin-bottom: clamp(18px, 1.6vw, 34px);

            font-size: clamp(14px, 1vw, 20px);
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;

            color: var(--ph-verde-claro);
        }

        .ph-login__eyebrow::before {
            content: "";
            width: clamp(34px, 2.6vw, 56px);
            height: 2px;
            border-radius: 10px;
            background: var(--ph-verde-claro);
        }

        .ph-login__title {
            margin: 0;

            font-size: clamp(48px, 5.8vw, 120px);
            line-height: .98;
            font-weight: 700;
            letter-spacing: -.035em;
        }

        .ph-login__title em {
            color: var(--ph-verde-claro);
            font-style: normal;
        }

        .ph-login__description {
            max-width: clamp(520px, 40vw, 820px);

            margin: clamp(22px, 2vw, 42px) 0 0;

            font-size: clamp(18px, 1.55vw, 32px);
            line-height: 1.5;

            color: rgba(255,255,255,.9);
        }

        /* =========================================================
           TARJETAS (glassmorphism)
        ========================================================= */

        .ph-login__cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: clamp(12px, 1vw, 24px);

            margin-top: clamp(28px, 3vw, 64px);
        }

        .ph-login__card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;

            min-height: clamp(170px, 11vw, 270px);
            padding: clamp(18px, 1.5vw, 32px);

            border: 1px solid rgba(255,255,255,.2);
            border-radius: clamp(18px, 1.4vw, 30px);

            background: rgba(0, 60, 52, .5);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);

            transition:
                transform .2s ease,
                background .2s ease;
        }

        .ph-login__card:hover {
            transform: translateY(-4px);
            background: rgba(0, 60, 52, .68);
        }

        /* Icono: contenedor flex con tamano fijo e identico en las 3 tarjetas */
        .ph-login__card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: none;

            width: clamp(50px, 3.6vw, 80px);
            height: clamp(50px, 3.6vw, 80px);
            margin: 0 0 clamp(14px, 1.1vw, 24px);
            padding: 0;

            border-radius: clamp(13px, 1vw, 22px);

            background: rgba(168,230,163,.18);
            color: var(--ph-verde-claro);

            line-height: 0;
        }

        .ph-login__card-icon svg {
            display: block;
            flex: none;
            width: 52%;
            height: 52%;
            margin: 0;
        }

        .ph-login__card-body {
            display: flex;
            flex-direction: column;
            gap: clamp(6px, .5vw, 12px);
        }

        .ph-login__card-title {
            display: block;

            font-size: clamp(16px, 1.3vw, 28px);
            font-weight: 700;
            line-height: 1.25;
        }

        .ph-login__card-text {
            display: block;

            font-size: clamp(13.5px, .98vw, 20px);
            line-height: 1.5;

            color: rgba(255,255,255,.78);
        }

        /* =========================================================
           FRASE INFERIOR
        ========================================================= */

        .ph-login__quote {
            display: flex;
            align-items: center;
            gap: 16px;

            width: fit-content;
            max-width: 100%;

            margin-top: clamp(26px, 2.4vw, 52px);
            padding: clamp(14px, 1vw, 22px) clamp(19px, 1.5vw, 32px);

            border-left: 3px solid var(--ph-verde-claro);

            background: rgba(0, 45, 39, .42);
            border-radius: 0 16px 16px 0;
        }

        .ph-login__quote svg {
            display: block;
            flex: none;
            width: clamp(24px, 1.8vw, 38px);
            height: clamp(24px, 1.8vw, 38px);
            color: var(--ph-verde-claro);
        }

        .ph-login__quote span {
            font-size: clamp(14.5px, 1.05vw, 22px);
            line-height: 1.4;
            color: rgba(255,255,255,.9);
        }

        /* =========================================================
           COLUMNA DERECHA
        ========================================================= */

        .ph-login__form-area {
            position: relative;

            display: flex;
            justify-content: center;
            align-items: stretch;

            background: #fff;

            overflow-y: auto;
        }

        .ph-login__decoration {
            position: absolute;
            top: 0;
            right: 0;

            width: clamp(180px, 13vw, 300px);
            height: clamp(180px, 13vw, 300px);

            pointer-events: none;
        }

        /* Formulario: ocupa la altura y distribuye el contenido */
        .ph-login__form {
            position: relative;
            z-index: 2;

            display: flex;
            flex-direction: column;
            justify-content: center;

            width: min(88%, clamp(520px, 40vw, 900px));
            min-height: 100dvh;

            padding: clamp(40px, 6vh, 100px) 0;
        }

        /* =========================================================
           LOGO
        ========================================================= */

        .ph-login__logo {
            display: inline-flex;
            align-items: center;
            gap: clamp(12px, 1vw, 22px);

            width: fit-content;

            margin-bottom: clamp(34px, 5vh, 80px);
        }

        .ph-login__logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: none;

            width: clamp(60px, 4.4vw, 100px);
            height: clamp(60px, 4.4vw, 100px);

            border-radius: clamp(16px, 1.3vw, 28px);

            background: var(--ph-verde-oscuro);
            color: white;

            line-height: 0;
        }

        .ph-login__logo-icon svg,
        .ph-login__logo-icon > * {
            display: block;
            flex: none;
            width: 56%;
            height: 56%;
            margin: 0;
        }

        .ph-login__logo-text {
            font-size: clamp(34px, 2.7vw, 62px);
            line-height: 1;
            color: var(--ph-tinta);
            font-weight: 700;
        }

        /* =========================================================
           INDICADOR «ACCESO A TU CUENTA»
           Se inserta justo encima del campo «Correo electrónico».
           El texto llega por la variable --ph-welcome-text (Blade).
        ========================================================= */

        .ph-login__form [data-flux-field]:has(input[type="email"])::before {
            content: var(--ph-welcome-text);

            display: block;

            margin-bottom: clamp(10px, .9vw, 16px);
            padding-left: clamp(19px, 1.4vw, 26px);

            color: var(--ph-verde);

            font-size: clamp(15px, 1.05vw, 21px);
            font-weight: 700;
            line-height: 1.3;

            background-image: radial-gradient(
                circle at center,
                var(--ph-verde) 0 48%,
                transparent 52%
            );
            background-repeat: no-repeat;
            background-position: left center;
            background-size: clamp(9px, .6vw, 13px) clamp(9px, .6vw, 13px);
        }

        /* =========================================================
           CONTENIDO DEL $slot (titulo, inputs, boton, avisos)
        ========================================================= */

        .ph-login__form :where(h1, h2) {
            font-size: clamp(34px, 3.3vw, 76px);
            line-height: 1.08;
            letter-spacing: -.02em;
        }

        .ph-login__form :where(p, a, label, small) {
            font-size: var(--ph-fs-body);
            line-height: 1.5;
        }

        .ph-login__form label,
        .ph-login__form [data-flux-label] {
            font-size: var(--ph-fs-body);
            font-weight: 600;
        }

        .ph-login__form input:not([type="checkbox"]):not([type="radio"]) {
            min-height: var(--ph-control-h);
            padding-inline: clamp(14px, 1.1vw, 24px);
            font-size: var(--ph-fs-body);
            border-radius: var(--ph-radius);
        }

        .ph-login__form input::placeholder {
            font-size: var(--ph-fs-body);
            opacity: .75;
        }

        .ph-login__form input[type="checkbox"] {
            width: clamp(20px, 1.4vw, 30px);
            height: clamp(20px, 1.4vw, 30px);
        }

        .ph-login__form button.w-full,
        .ph-login__form button[data-test="login-button"] {
            min-height: var(--ph-control-h);
            font-size: var(--ph-fs-body);
            font-weight: 700;
            border-radius: var(--ph-radius);
        }

        /* Iconos de Flux en botones: sin tocar "display" para que Flux
           pueda ocultar/mostrar el ojo y el ojo tachado segun su estado */
        .ph-login__form button svg {
            flex: none;
        }

        /* ---------------------------------------------------------
           Campo de contrasena: boton del ojo
        --------------------------------------------------------- */

        /* Reserva espacio a la derecha para que el texto no quede bajo el icono */
        .ph-login__form input[type="password"],
        .ph-login__form :is(div, span):has(> input):has(button) > input {
            padding-inline-end: calc(var(--ph-eye-size) + clamp(14px, 1vw, 22px));
        }

        /* Boton: area de toque comoda, icono centrado, pegado al extremo derecho */
        .ph-login__form :is(div, span):has(> input) button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: var(--ph-eye-size);
            height: var(--ph-eye-size);
            min-width: var(--ph-eye-size);
            min-height: var(--ph-eye-size);
            padding: 0;
            margin: 0;

            line-height: 0;
            border-radius: calc(var(--ph-radius) - 2px);
        }

        .ph-login__form :is(div, span):has(> input) button svg {
            width: clamp(19px, 1.35vw, 26px);
            height: clamp(19px, 1.35vw, 26px);
            margin: 0;
        }

        /* ---------------------------------------------------------
           Etiqueta "Contrasena" + enlace "Olvide mi contrasena"
        --------------------------------------------------------- */

        .ph-login__form a[href*="forgot"],
        .ph-login__form a.absolute {
            display: inline-flex;
            align-items: center;

            top: 0;
            max-width: 55%;

            font-size: var(--ph-fs-body);
            line-height: 1.5;
            text-align: end;
        }

        .ph-login__form [data-flux-label] {
            line-height: 1.5;
        }

        /* Espaciado interno de los contenedores del formulario */
        .ph-login__form form {
            display: flex;
            flex-direction: column;
            gap: clamp(18px, 2.4vh, 36px);
        }

        /* =========================================================
           ENLACE REGISTRO
        ========================================================= */

        .ph-login__register {
            margin-top: clamp(22px, 3vh, 44px);

            text-align: center;

            font-size: var(--ph-fs-body);
            color: var(--ph-gris);
        }

        .ph-login__register a {
            color: var(--ph-verde);
            font-weight: 700;
            text-decoration: none;
        }

        .ph-login__register a:hover {
            text-decoration: underline;
        }

        /* =========================================================
           RESPONSIVE - TABLET GRANDE / LAPTOP PEQUENO
        ========================================================= */

        @media (max-width: 1100px) {

            .ph-login__layout {
                grid-template-columns: 50% 50%;
            }

            .ph-login__hero {
                max-width: 100%;
            }

            /* Tarjetas en una columna, con el icono a la izquierda */
            .ph-login__cards {
                grid-template-columns: 1fr;
                max-width: 480px;
            }

            .ph-login__card {
                flex-direction: row;
                align-items: center;
                gap: 16px;
                min-height: auto;
            }

            .ph-login__card-icon {
                margin: 0;
                width: 54px;
                height: 54px;
            }

            .ph-login__form {
                width: min(90%, 560px);
            }
        }

        /* =========================================================
           RESPONSIVE - TABLET / MOVIL (una columna)
        ========================================================= */

        @media (max-width: 900px) {

            .ph-login__layout {
                display: block;
                min-height: 100dvh;
            }

            .ph-login__visual {
                min-height: auto;
            }

            .ph-login__content {
                min-height: auto;
                gap: 28px;
            }

            .ph-login__hero {
                margin: 0;
                padding-top: 12px;
            }

            .ph-login__title {
                font-size: clamp(40px, 9vw, 72px);
            }

            .ph-login__description {
                font-size: clamp(17px, 2.8vw, 22px);
            }

            .ph-login__cards {
                max-width: 560px;
                margin-top: 28px;
            }

            .ph-login__card-title {
                font-size: 17px;
            }

            .ph-login__card-text {
                font-size: 14px;
            }

            .ph-login__quote {
                margin-top: 24px;
            }

            .ph-login__form-area {
                min-height: auto;
            }

            .ph-login__form {
                width: min(90%, 680px);
                min-height: auto;
                padding: 56px 0 48px;

                --ph-fs-body: 16px;
                --ph-fs-small: 14px;
                --ph-control-h: 56px;
            }

            .ph-login__form :where(h1, h2) {
                font-size: clamp(32px, 6vw, 44px);
            }

            .ph-login__logo {
                margin-bottom: 32px;
            }

            .ph-login__logo-icon {
                width: 64px;
                height: 64px;
            }

            .ph-login__logo-text {
                font-size: 36px;
            }

            .ph-login__decoration {
                width: 150px;
                height: 150px;
            }
        }

        /* =========================================================
           RESPONSIVE - MOVIL
        ========================================================= */

        @media (max-width: 600px) {

            .ph-login__content {
                padding: 22px 22px 34px;
            }

            .ph-login__back {
                font-size: 14px;
                padding: 10px 15px;
            }

            .ph-login__eyebrow {
                font-size: 13px;
            }

            .ph-login__description {
                font-size: 16px;
                margin-top: 16px;
            }

            .ph-login__card {
                padding: 16px;
            }

            .ph-login__card-icon {
                width: 48px;
                height: 48px;
            }

            .ph-login__quote span {
                font-size: 14px;
            }

            .ph-login__form {
                width: calc(100% - 40px);
                padding: 44px 0 40px;
            }

            .ph-login__logo-icon {
                width: 58px;
                height: 58px;
            }

            .ph-login__logo-text {
                font-size: 31px;
            }

            .ph-login__form :where(h1, h2) {
                font-size: 32px;
            }

            .ph-login__form {
                --ph-eye-size: 44px;
            }

            .ph-login__form a[href*="forgot"],
            .ph-login__form a.absolute {
                font-size: 14px;
                max-width: 60%;
                text-align: end;
            }
        }

        @media (max-width: 360px) {

            .ph-login__form a[href*="forgot"],
            .ph-login__form a.absolute {
                font-size: 13px;
                max-width: 58%;
            }
        }
    </style>
</head>

<body class="ph-login min-h-screen bg-white antialiased">

    <div class="ph-login__layout">

        {{-- =====================================================
             COLUMNA VISUAL
        ====================================================== --}}
        <section class="ph-login__visual">

            <div class="ph-login__background"></div>

            <img
                src="{{ asset('images/aW5pY2lvcGVycm9nYXRv.jpg') }}"
                alt="{{ __('PeliHuellas') }}"
                class="ph-login__image"
                loading="eager"
            >

            <div class="ph-login__overlay"></div>

            <div class="ph-login__content">

                {{-- Navegación --}}
                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="ph-login__back"
                >
                    <flux:icon.arrow-left variant="mini" />
                    {{ __('Volver al inicio') }}
                </a>

                {{-- Mensaje --}}
                <div class="ph-login__hero">

                    <div class="ph-login__eyebrow">
                        {{ __('Bienvenido de nuevo') }}
                    </div>

                    <h1 class="pf-serif ph-login__title">
                        {{ __('Qué bueno verte') }}
                        <br>
                        <em>{{ __('de nuevo') }}</em>
                    </h1>

                    <p class="ph-login__description">
                        {{ __('Vuelve a PeliHuellas y continúa formando parte de nuevas historias.') }}
                    </p>

                    {{-- Tarjetas exclusivas del Login --}}
                    <div class="ph-login__cards">

                        <div class="ph-login__card">

                            <span class="ph-login__card-icon">
                                <flux:icon.arrow-path />
                            </span>

                            <div class="ph-login__card-body">
                                <strong class="ph-login__card-title">
                                    {{ __('Continúa tu historia') }}
                                </strong>

                                <span class="ph-login__card-text">
                                    {{ __('Retoma tus actividades donde las dejaste.') }}
                                </span>
                            </div>

                        </div>

                        <div class="ph-login__card">

                            <span class="ph-login__card-icon">
                                <flux:icon.sparkles />
                            </span>

                            <div class="ph-login__card-body">
                                <strong class="ph-login__card-title">
                                    {{ __('Nuevas oportunidades') }}
                                </strong>

                                <span class="ph-login__card-text">
                                    {{ __('Descubre nuevas formas de ayudar y conectar.') }}
                                </span>
                            </div>

                        </div>

                        <div class="ph-login__card">

                            <span class="ph-login__card-icon">
                                <flux:icon.chat-bubble-left-right />
                            </span>

                            <div class="ph-login__card-body">
                                <strong class="ph-login__card-title">
                                    {{ __('Tu comunidad') }}
                                </strong>

                                <span class="ph-login__card-text">
                                    {{ __('Vuelve a conectar con quienes hacen la diferencia.') }}
                                </span>
                            </div>

                        </div>

                    </div>

                    {{-- Frase inferior --}}
                    <div class="ph-login__quote">

                        <flux:icon.heart variant="solid" />

                        <span>
                            {{ __('Tu próxima historia puede comenzar aquí.') }}
                        </span>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             COLUMNA DEL FORMULARIO
        ====================================================== --}}
        <section class="ph-login__form-area">

            {{-- Decoración --}}
            <svg
                class="ph-login__decoration"
                viewBox="0 0 180 180"
                fill="#CFEBDD"
                aria-hidden="true"
            >
                <ellipse
                    cx="120"
                    cy="25"
                    rx="30"
                    ry="20"
                    transform="rotate(-20 120 25)"
                />

                <ellipse
                    cx="155"
                    cy="85"
                    rx="35"
                    ry="22"
                    transform="rotate(25 155 85)"
                />

                <ellipse
                    cx="90"
                    cy="105"
                    rx="23"
                    ry="15"
                    transform="rotate(-15 90 105)"
                />
            </svg>


            <div
                class="ph-login__form"
                style="--ph-welcome-text: '{{ addslashes(__('Acceso a tu cuenta')) }}';"
            >

                {{-- Logo --}}
                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="ph-login__logo"
                >

                    <span class="ph-login__logo-icon">
                        <x-icono-huella />
                    </span>

                    <span class="pf-serif ph-login__logo-text">
                        PeliHuellas
                    </span>

                </a>


                {{-- =================================================
                     FORMULARIO ORIGINAL
                     $slot mantiene el contenido del LOGIN
                ================================================== --}}
                {{ $slot }}

            </div>

        </section>

    </div>


    @persist('toast')

        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>

    @endpersist


    @fluxScripts

</body>

</html>