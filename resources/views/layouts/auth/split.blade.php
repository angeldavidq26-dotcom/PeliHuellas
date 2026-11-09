<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        {{--
            Los tamaños usan clamp() con vh para que la pantalla completa quepa sin scroll
            en escritorio (desde ~720px de alto útil) y crezca en monitores grandes.

            Panel izquierdo: la altura se reparte con un "presupuesto" en vh (titulo, beneficios y
            estadisticas dejan libre ~17vh). Ese espacio lo ocupa .ph-auth__centro, que centra la
            frase emocional entre los beneficios y las estadisticas sin posiciones absolutas.
            Si la pantalla es baja, la frase se simplifica o se oculta (media queries max-height).
        --}}
        <style>
            .ph-auth {
                --ph-verde: #006B5B;
                --ph-verde-oscuro: #005548;
                --ph-verde-claro: #A8E6A3;
                --ph-verde-suave: #E8F5F0;
                --ph-tinta: #0F3B33;
                --ph-gris: #6B7C78;

                /* Paleta del panel izquierdo */
                --ph-izq-principal: #1B5C4F;
                --ph-izq-profundo: #073F36;
            }

            /* ---------- Columna izquierda ---------- */
            /* La misma foto desenfocada extiende el paisaje por detrás del texto. */
            .ph-auth__foto-fondo {
                position: absolute;
                inset: -40px;
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center 30%;
                filter: blur(20px);
            }

            /*
             * La foto es vertical (2:3): se ancla abajo y se desplaza para que las caras de
             * perro y gato (entre el 38% y el 62% del ancho de la imagen) empiecen siempre
             * al 64% de la columna, lejos del titular, sea cual sea la proporción de pantalla.
             * El max() con vw garantiza que en pantallas muy anchas llegue al borde derecho.
             */
            .ph-auth__foto {
                --alto: max(122vh, 56vw);
                position: absolute;
                bottom: 0;
                left: calc(64% - var(--alto) / 1.5 * 0.38);
                width: auto;
                max-width: none;
                height: var(--alto);
                -webkit-mask-image: linear-gradient(to right, transparent 0%, #000 34%);
                mask-image: linear-gradient(to right, transparent 0%, #000 34%);
            }

            /* Degradado verde oscuro de izquierda a derecha + base oscura para estadisticas y lema. */
            .ph-auth__velo {
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(to top, rgba(7, 63, 54, 0.7) 0%, rgba(7, 63, 54, 0.25) 30%, rgba(7, 63, 54, 0) 48%),
                    linear-gradient(to right, rgba(7, 63, 54, 0.9) 0%, rgba(7, 63, 54, 0.66) 36%, rgba(7, 63, 54, 0.22) 60%, rgba(7, 63, 54, 0.1) 100%);
            }

            .ph-auth__izq {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                height: 100%;
                padding:
                    clamp(16px, 2.4vh, 30px)
                    clamp(28px, 3.2vw, 76px)
                    clamp(22px, 4.2vh, 60px);
                color: #fff;
            }

            .ph-auth__volver {
                display: inline-flex;
                align-items: center;
                align-self: flex-start;
                gap: 12px;
                height: clamp(44px, 6.4vh, 68px);
                padding: 0 clamp(22px, 2vw, 36px);
                border: 1px solid rgba(168, 230, 163, 0.45);
                border-radius: 999px;
                background: rgba(7, 63, 54, 0.55);
                backdrop-filter: blur(6px);
                color: #fff;
                font-size: clamp(15px, 2.1vh, 20px);
                font-weight: 600;
                transition: background-color 0.2s ease;
            }

            .ph-auth__volver:hover {
                background: rgba(7, 63, 54, 0.85);
            }

            .ph-auth__volver svg {
                width: 20px;
                height: 20px;
            }

            .ph-auth__marca {
                margin-top: clamp(10px, 1.5vh, 22px);
                max-width: 32em; /* Ampliado para permitir que el texto respire y se extienda a la derecha */
                font-size: clamp(16px, min(2.05vh, 1.45vw), 27px);
                line-height: 1.45;
                color: rgba(255, 255, 255, 0.92);
            }

            .ph-auth__logo {
                display: inline-flex;
                align-items: center;
                gap: clamp(12px, 1.6vh, 20px);
            }

            .ph-auth__logo-badge {
                display: flex;
                flex: none;
                align-items: center;
                justify-content: center;
                border-radius: 22%;
                background: var(--ph-verde-oscuro);
                color: #fff;
            }

            .ph-auth__logo-badge svg {
                width: 62%;
                height: 62%;
            }

            .ph-auth__logo-texto {
                font-weight: 700;
                line-height: 1;
            }

            /* Título ensanchado horizontalmente y ligeramente más grande */
            .ph-auth__titulo {
                font-size: clamp(38px, min(7vh, 4.2vw), 96px);
                font-weight: 700;
                line-height: 1.03;
            }

            .ph-auth__titulo em {
                font-style: normal;
                color: var(--ph-verde-claro);
            }

            .ph-auth__descripcion {
                max-width: 32em;
                margin-top: clamp(10px, 1.5vh, 22px);
                font-size: clamp(16px, min(2.05vh, 1.45vw), 27px);
                line-height: 1.45;
                color: rgba(255, 255, 255, 0.92);
            }

            .ph-auth__beneficios {
                display: flex;
                flex-direction: column;
                gap: clamp(10px, 1.6vh, 26px);
                margin-top: clamp(14px, 2.6vh, 40px);
            }

            .ph-auth__beneficio {
                display: flex;
                align-items: center;
                gap: clamp(14px, 1.2vw, 24px);
            }

            .ph-auth__beneficio-icono {
                display: flex;
                flex: none;
                align-items: center;
                justify-content: center;
                width: clamp(50px, min(6.6vh, 4vw), 96px);
                height: clamp(50px, min(6.6vh, 4vw), 96px);
                border: 1px solid rgba(168, 230, 163, 0.4);
                border-radius: 50%;
                background: rgba(27, 92, 79, 0.78);
                color: var(--ph-verde-claro);
            }

            .ph-auth__beneficio-icono svg {
                width: 48%;
                height: 48%;
            }

            .ph-auth__beneficio > div > strong {
                display: block;
                font-size: clamp(16.5px, min(2.3vh, 1.45vw), 28px);
                font-weight: 700;
                line-height: 1.2;
            }

            .ph-auth__beneficio > div > span {
                display: block;
                font-size: clamp(14px, min(1.85vh, 1.15vw), 22px);
                line-height: 1.35;
                color: rgba(255, 255, 255, 0.82);
            }

            /*
             * Zona central: ocupa el espacio libre entre beneficios y estadisticas
             * y centra en ella la frase emocional (flexbox, sin posiciones absolutas).
             */
            .ph-auth__centro {
                display: flex;
                flex: 1 1 auto;
                align-items: center;
                min-height: 0;
                padding-block: clamp(8px, 1.2vh, 20px);
            }

            .ph-auth__lema {
                display: flex;
                flex-direction: column;
                gap: clamp(8px, 1.2vh, 18px);
                max-width: 24em;
                font-size: clamp(21px, min(3.1vh, 2.1vw), 40px);
                text-shadow: 0 2px 16px rgba(0, 40, 34, 0.55);
            }

            .ph-auth__stats {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                width: 100%;
                max-width: clamp(600px, 36vw, 900px);
                margin-top: auto;
                padding: clamp(14px, 1.9vh, 30px) clamp(18px, 2.2vw, 44px);
                border: 1px solid rgba(168, 230, 163, 0.2);
                border-radius: clamp(22px, 3.4vh, 40px);
                background: rgba(7, 63, 54, 0.66);
                backdrop-filter: blur(10px);
            }

            .ph-auth__stat + .ph-auth__stat {
                padding-left: clamp(14px, 1.8vw, 34px);
                border-left: 1px solid rgba(255, 255, 255, 0.22);
            }

            .ph-auth__stat-cifra {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .ph-auth__stat-cifra svg {
                flex: none;
                width: clamp(28px, min(4vh, 2.3vw), 56px);
                height: clamp(28px, min(4vh, 2.3vw), 56px);
                color: var(--ph-verde-claro);
            }

            .ph-auth__stat-cifra strong {
                font-size: clamp(28px, min(4vh, 2.3vw), 56px);
                font-weight: 700;
                font-variant-numeric: lining-nums;
                line-height: 1;
            }

            .ph-auth__stat-titulo {
                margin-top: 6px;
                font-size: clamp(14.5px, min(1.95vh, 1.1vw), 22px);
                font-weight: 700;
                line-height: 1.25;
            }

            .ph-auth__stat-detalle {
                font-size: clamp(12.5px, min(1.6vh, .95vw), 17px);
                line-height: 1.3;
                color: rgba(255, 255, 255, 0.82);
            }

            /* Pantallas de poca altura */
            @media (max-height: 859px) {
                .ph-auth__huellas,
                .ph-auth__lema-sec {
                    display: none;
                }
            }

            @media (max-height: 639px) {
                .ph-auth__lema {
                    display: none;
                }
            }

            /* ---------- Columna derecha ---------- */
            .ph-auth__der {
                position: relative;
                display: flex;
                justify-content: center;
                background: #fff;
                overflow-x: hidden;
            }

            .ph-auth__deco {
                position: absolute;
                top: 0;
                right: 0;
                width: clamp(90px, 8.5vw, 150px);
                pointer-events: none;
            }

            .ph-auth__der-contenido {
                position: relative;
                display: flex;
                flex-direction: column;
                width: 86%;
                max-width: 720px;
                margin: auto 0;
                padding: clamp(16px, 2.2vh, 32px) 0;
            }

            .ph-auth__logo--oscuro .ph-auth__logo-badge {
                width: clamp(40px, 6vh, 64px);
                height: clamp(40px, 6vh, 64px);
            }

            .ph-auth__logo--oscuro .ph-auth__logo-texto {
                font-size: clamp(28px, 4.2vh, 40px);
                color: var(--ph-tinta);
            }

            .ph-auth__volver-movil {
                display: inline-flex;
                align-items: center;
                align-self: flex-start;
                gap: 8px;
                margin-bottom: 20px;
                padding: 10px 18px;
                border: 1px solid rgba(0, 107, 91, 0.2);
                border-radius: 999px;
                background: var(--ph-verde-suave);
                color: var(--ph-verde-oscuro);
                font-size: 14px;
                font-weight: 600;
            }

            @media (min-width: 1024px) {
                .ph-auth__volver-movil {
                    display: none;
                }
            }

            @media (max-width: 1023px) {
                .ph-auth__der-contenido {
                    width: 100%;
                    max-width: 560px;
                    padding: 28px 20px 40px;
                }
            }
        </style>
    </head>
    <body class="ph-auth min-h-screen bg-white antialiased">
        <div class="grid min-h-dvh lg:h-dvh lg:grid-cols-[61fr_39fr] lg:overflow-hidden">
            {{-- Columna izquierda: fotografía con velo verde e identidad de marca. --}}
            <div class="relative hidden overflow-hidden lg:block">
                <div class="ph-auth__foto-fondo" style="background-image: url('{{ asset('images/Q3JlYXJDdWVudGFHUA==.jpg') }}');"></div>
                <img
                    src="{{ asset('images/Q3JlYXJDdWVudGFHUA==.jpg') }}"
                    alt="{{ __('Un perro golden y un gato atigrado juntos en la montaña') }}"
                    class="ph-auth__foto"
                    loading="eager"
                >
                <div class="ph-auth__velo"></div>

                <div class="ph-auth__izq">
                    <a href="{{ route('home') }}" wire:navigate class="ph-auth__volver">
                        <flux:icon.arrow-left variant="mini" />
                        {{ __('Volver al inicio') }}
                    </a>

                    <div class="ph-auth__marca">
                        <h1 class="pf-serif ph-auth__titulo">
                            {{ __('Más que adopciones,') }}
                            {{ __('son') }} <em>{{ __('nuevas historias') }}</em>
                            {{ __('por vivir.') }}
                        </h1>

                        <p class="ph-auth__descripcion"><br>
                            {{ __('Conecta con fundaciones, adopta o apoya') }}
                            {{ __('a los animales que más lo necesitan.') }}
                        </p>

                        <ul class="ph-auth__beneficios">
                            <li class="ph-auth__beneficio">
                                <span class="ph-auth__beneficio-icono"><flux:icon.heart variant="solid" /></span>
                                <div>
                                    <strong>{{ __('Adopta') }}</strong>
                                    <span>{{ __('Dales una segunda oportunidad') }}</span>
                                </div>
                            </li>
                            <li class="ph-auth__beneficio">
                                <span class="ph-auth__beneficio-icono"><flux:icon.user-group variant="solid" /></span>
                                <div>
                                    <strong>{{ __('Apoya') }}</strong>
                                    <span>{{ __('Haz la diferencia con tu ayuda') }}</span>
                                </div>
                            </li>
                            <li class="ph-auth__beneficio">
                                <span class="ph-auth__beneficio-icono"><flux:icon.shield-check variant="solid" /></span>
                                <div>
                                    <strong>{{ __('Conecta') }}</strong>
                                    <span>{{ __('Con fundaciones y veterinarias confiables') }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Zona central: frase emocional que aprovecha el espacio libre. --}}
                    <div class="ph-auth__centro">
                        <div class="ph-auth__lema">
                        </div>
                    </div>

                    <div class="ph-auth__stats">
                        <div class="ph-auth__stat">
                            <div class="ph-auth__stat-cifra">
                                <x-icono-huella />
                                <strong class="pf-serif">3.841</strong>
                            </div>
                            <p class="ph-auth__stat-titulo">{{ __('Animales') }}</p>
                            <p class="ph-auth__stat-detalle">{{ __('en búsqueda de hogar') }}</p>
                        </div>
                        <div class="ph-auth__stat">
                            <div class="ph-auth__stat-cifra">
                                <flux:icon.heart variant="solid" />
                                <strong class="pf-serif">127</strong>
                            </div>
                            <p class="ph-auth__stat-titulo">{{ __('Fundaciones') }}</p>
                            <p class="ph-auth__stat-detalle">{{ __('verificadas') }}</p>
                        </div>
                        <div class="ph-auth__stat">
                            <div class="ph-auth__stat-cifra">
                                <flux:icon.shield-check variant="solid" />
                                <strong class="pf-serif">89</strong>
                            </div>
                            <p class="ph-auth__stat-titulo">{{ __('Veterinarias') }}</p>
                            <p class="ph-auth__stat-detalle">{{ __('de confianza') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna derecha: formulario. --}}
            <div class="ph-auth__der min-h-dvh lg:min-h-0 lg:overflow-y-auto">
                <svg class="ph-auth__deco" viewBox="0 0 150 130" fill="#CFEBDD" aria-hidden="true">
                    <ellipse cx="66" cy="6" rx="24" ry="16" transform="rotate(-15 66 6)" />
                    <ellipse cx="34" cy="60" rx="25" ry="16" transform="rotate(-12 34 60)" />
                    <ellipse cx="128" cy="56" rx="32" ry="54" transform="rotate(16 128 56)" />
                </svg>

                <div class="ph-auth__der-contenido">
                    <a href="{{ route('home') }}" wire:navigate class="ph-auth__volver-movil">
                        <span aria-hidden="true">&larr;</span> {{ __('Volver al inicio') }}
                    </a>

                    <a href="{{ route('home') }}" wire:navigate class="ph-auth__logo ph-auth__logo--oscuro">
                        <span class="ph-auth__logo-badge"><x-icono-huella /></span>
                        <span class="pf-serif ph-auth__logo-texto">PeliHuellas</span>
                    </a>

                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>