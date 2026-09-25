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

        /*
         * Degradado diferente al Register:
         * aquí la fotografía tiene mayor protagonismo.
         */
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
            display: flex;
            flex-direction: column;

            padding:
                clamp(28px, 4vh, 48px)
                clamp(30px, 4vw, 65px)
                clamp(30px, 5vh, 60px);

            color: white;
        }

        /* =========================================================
           NAVEGACIÓN
        ========================================================= */

        .ph-login__back {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            width: fit-content;

            padding: 11px 18px;

            border: 1px solid rgba(255,255,255,.28);
            border-radius: 14px;

            background: rgba(0, 60, 52, .45);
            backdrop-filter: blur(10px);

            color: white;

            font-size: 15px;
            font-weight: 600;

            transition:
                background .2s ease,
                transform .2s ease;
        }

        .ph-login__back:hover {
            background: rgba(0, 60, 52, .72);
            transform: translateX(-3px);
        }

        /* =========================================================
           MENSAJE PRINCIPAL
        ========================================================= */

        .ph-login__hero {
            max-width: 650px;
            margin-top: auto;
            margin-bottom: auto;
            padding-top: 5vh;
        }

        .ph-login__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;

            margin-bottom: 20px;

            font-size: 14px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;

            color: var(--ph-verde-claro);
        }

        .ph-login__eyebrow::before {
            content: "";
            width: 34px;
            height: 2px;
            border-radius: 10px;
            background: var(--ph-verde-claro);
        }

        .ph-login__title {
            margin: 0;

            font-size: clamp(42px, 5.5vw, 76px);
            line-height: .98;
            font-weight: 700;
            letter-spacing: -.035em;
        }

        .ph-login__title em {
            color: var(--ph-verde-claro);
            font-style: normal;
        }

        .ph-login__description {
            max-width: 570px;

            margin-top: 25px;

            font-size: clamp(17px, 1.5vw, 21px);
            line-height: 1.55;

            color: rgba(255,255,255,.88);
        }

        /* =========================================================
           TARJETAS DE LOGIN
        ========================================================= */

        .ph-login__cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;

            margin-top: 34px;
        }

        .ph-login__card {
            padding: 18px;

            min-height: 145px;

            border: 1px solid rgba(255,255,255,.17);
            border-radius: 20px;

            background: rgba(0, 60, 52, .48);
            backdrop-filter: blur(12px);

            transition:
                transform .2s ease,
                background .2s ease;
        }

        .ph-login__card:hover {
            transform: translateY(-4px);
            background: rgba(0, 60, 52, .65);
        }

        .ph-login__card-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 42px;
            height: 42px;

            margin-bottom: 13px;

            border-radius: 12px;

            background: rgba(168,230,163,.16);
            color: var(--ph-verde-claro);
        }

        .ph-login__card-icon svg {
            width: 21px;
            height: 21px;
        }

        .ph-login__card strong {
            display: block;

            font-size: 15px;
            line-height: 1.25;
        }

        .ph-login__card span {
            display: block;

            margin-top: 7px;

            font-size: 12.5px;
            line-height: 1.45;

            color: rgba(255,255,255,.72);
        }

        /* =========================================================
           FRASE INFERIOR
        ========================================================= */

        .ph-login__quote {
            display: flex;
            align-items: center;
            gap: 16px;

            width: fit-content;

            margin-top: 35px;
            padding: 14px 19px;

            border-left: 3px solid var(--ph-verde-claro);

            background: rgba(0, 45, 39, .38);
            border-radius: 0 15px 15px 0;
        }

        .ph-login__quote svg {
            width: 25px;
            height: 25px;
            color: var(--ph-verde-claro);
            flex: none;
        }

        .ph-login__quote span {
            font-size: 14px;
            color: rgba(255,255,255,.86);
        }

        /* =========================================================
           COLUMNA DERECHA
        ========================================================= */

        .ph-login__form-area {
            position: relative;

            display: flex;
            justify-content: center;
            align-items: center;

            background: #fff;

            overflow-y: auto;
        }

        .ph-login__decoration {
            position: absolute;
            top: 0;
            right: 0;

            width: 180px;
            height: 180px;

            pointer-events: none;
        }

        .ph-login__form {
            position: relative;
            z-index: 2;

            width: min(86%, 500px);

            padding: 40px 0;
        }

        /* =========================================================
           LOGO
        ========================================================= */

        .ph-login__logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;

            margin-bottom: 42px;
        }

        .ph-login__logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 52px;
            height: 52px;

            border-radius: 16px;

            background: var(--ph-verde-oscuro);
            color: white;
        }

        .ph-login__logo-icon svg {
            width: 29px;
            height: 29px;
        }

        .ph-login__logo-text {
            font-size: 31px;
            line-height: 1;
            color: var(--ph-tinta);
            font-weight: 700;
        }

        /* =========================================================
           CABECERA DEL LOGIN
        ========================================================= */

        .ph-login__heading {
            margin-bottom: 30px;
        }

        .ph-login__heading h2 {
            margin: 0;

            font-size: clamp(31px, 4vw, 45px);
            line-height: 1.05;

            color: var(--ph-tinta);
            font-weight: 700;
        }

        .ph-login__heading p {
            max-width: 430px;

            margin-top: 12px;

            color: var(--ph-gris);

            font-size: 16px;
            line-height: 1.55;
        }

        /* =========================================================
           DETALLE VISUAL SUPERIOR
        ========================================================= */

        .ph-login__welcome {
            display: flex;
            align-items: center;
            gap: 10px;

            margin-bottom: 15px;

            color: var(--ph-verde);

            font-size: 14px;
            font-weight: 700;
        }

        .ph-login__welcome-dot {
            width: 8px;
            height: 8px;

            border-radius: 50%;

            background: var(--ph-verde);
        }

        /* =========================================================
           ENLACE REGISTRO
        ========================================================= */

        .ph-login__register {
            margin-top: 25px;

            text-align: center;

            font-size: 14px;
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
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1100px) {

            .ph-login__layout {
                grid-template-columns: 50% 50%;
            }

            .ph-login__cards {
                grid-template-columns: 1fr;
                max-width: 400px;
            }

            .ph-login__card {
                min-height: auto;
            }

            .ph-login__quote {
                display: none;
            }
        }

        @media (max-width: 900px) {

            .ph-login__layout {
                display: block;
                min-height: 100dvh;
            }

            .ph-login__visual {
                min-height: 380px;
            }

            .ph-login__hero {
                padding-top: 25px;
            }

            .ph-login__cards {
                display: none;
            }

            .ph-login__title {
                font-size: clamp(38px, 9vw, 60px);
            }

            .ph-login__form-area {
                min-height: auto;
            }
.ph-login__form {
    width: min(90%, 590px);
    padding: 55px 0 45px;
}

.ph-login__form input {
    min-height: 50px;
    font-size: 15px;
}

.ph-login__form label {
    font-size: 14px;
    font-weight: 600;
}

.ph-login__form button[data-test="login-button"] {
    min-height: 52px;
    font-size: 15px;
    font-weight: 700;
}


            .ph-login__logo {
                margin-bottom: 30px;
            }
        }

        @media (max-width: 600px) {

            .ph-login__visual {
                min-height: 330px;
            }

            .ph-login__content {
                padding:
                    22px
                    22px
                    35px;
            }

            .ph-login__back {
                font-size: 13px;
                padding: 9px 14px;
            }

            .ph-login__description {
                font-size: 15px;
                margin-top: 17px;
            }

            .ph-login__form {
                width: calc(100% - 40px);
            }

            .ph-login__logo-text {
                font-size: 27px;
            }

            .ph-login__heading h2 {
                font-size: 32px;
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

                    {{-- Contenido exclusivo del Login --}}
                    <div class="ph-login__cards">

                        <div class="ph-login__card">

                            <span class="ph-login__card-icon">
                                <flux:icon.arrow-path />
                            </span>

                            <strong>
                                {{ __('Continúa tu historia') }}
                            </strong>

                            <span>
                                {{ __('Retoma tus actividades donde las dejaste.') }}
                            </span>

                        </div>

                        <div class="ph-login__card">

                            <span class="ph-login__card-icon">
                                <flux:icon.sparkles />
                            </span>

                            <strong>
                                {{ __('Nuevas oportunidades') }}
                            </strong>

                            <span>
                                {{ __('Descubre nuevas formas de ayudar y conectar.') }}
                            </span>

                        </div>

                        <div class="ph-login__card">

                            <span class="ph-login__card-icon">
                                <flux:icon.chat-bubble-left-right />
                            </span>

                            <strong>
                                {{ __('Tu comunidad') }}
                            </strong>

                            <span>
                                {{ __('Vuelve a conectar con quienes hacen la diferencia.') }}
                            </span>

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


            <div class="ph-login__form">

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


                {{-- Cabecera --}}
                <div class="ph-login__heading">

                    <div class="ph-login__welcome">

                        <span class="ph-login__welcome-dot"></span>

                        {{ __('Acceso a tu cuenta') }}

                    </div>





                </div>


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

