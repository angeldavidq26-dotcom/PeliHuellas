<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        {{--
            Los tamaños usan clamp() con vh para que la pantalla completa quepa sin scroll
            en escritorio (desde ~720px de alto útil) y crezca en monitores grandes.
        --}}
        <style>
            .ph-auth {
                --ph-verde: #006B5B;
                --ph-verde-oscuro: #005548;
                --ph-verde-claro: #A8E6A3;
                --ph-verde-suave: #E8F5F0;
                --ph-tinta: #0F3B33;
                --ph-gris: #6B7C78;
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
                --alto: max(118vh, 54vw);
                position: absolute;
                bottom: 0;
                left: calc(64% - var(--alto) / 1.5 * 0.38);
                width: auto;
                max-width: none;
                height: var(--alto);
                -webkit-mask-image: linear-gradient(to right, transparent 0%, #000 34%);
                mask-image: linear-gradient(to right, transparent 0%, #000 34%);
            }

            .ph-auth__velo {
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(to top, rgba(0, 48, 41, 0.6) 0%, rgba(0, 48, 41, 0) 38%),
                    linear-gradient(to right, rgba(0, 72, 62, 0.84) 0%, rgba(0, 72, 62, 0.6) 36%, rgba(0, 72, 62, 0.2) 60%, rgba(0, 72, 62, 0.1) 100%);
            }

            .ph-auth__izq {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                height: 100%;
                padding: clamp(16px, 2.7vh, 28px) clamp(28px, 3.2vw, 60px) clamp(24px, 5.5vh, 56px);
                color: #fff;
            }

            .ph-auth__volver {
                display: inline-flex;
                align-items: center;
                align-self: flex-start;
                gap: 12px;
                height: clamp(44px, 7vh, 66px);
                padding: 0 clamp(22px, 2vw, 34px);
                border: 1px solid rgba(168, 230, 163, 0.45);
                border-radius: 999px;
                background: rgba(0, 85, 72, 0.55);
                backdrop-filter: blur(6px);
                color: #fff;
                font-size: clamp(15px, 2.2vh, 20px);
                font-weight: 600;
                transition: background-color 0.2s ease;
            }

            .ph-auth__volver:hover {
                background: rgba(0, 85, 72, 0.85);
            }

            .ph-auth__volver svg {
                width: 20px;
                height: 20px;
            }

            .ph-auth__marca {
                max-width: 820px;
                margin-top: clamp(20px, 5vh, 56px);
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

            /* Crece con el alto, pero el tope por ancho evita que llegue a las caras de la foto. */
            .ph-auth__titulo {
                font-size: clamp(36px, min(7.4vh, 3.62vw), 80px);
                font-weight: 700;
                line-height: 1.03;
            }

            .ph-auth__titulo em {
                font-style: normal;
                color: var(--ph-verde-claro);
            }

            .ph-auth__descripcion {
                max-width: 30em;
                margin-top: clamp(10px, 1.8vh, 20px);
                font-size: clamp(16px, 2.25vh, 22px);
                line-height: 1.45;
                color: rgba(255, 255, 255, 0.92);
            }

            .ph-auth__beneficios {
                display: flex;
                flex-direction: column;
                gap: clamp(10px, 1.7vh, 18px);
                margin-top: clamp(16px, 3vh, 34px);
            }

            .ph-auth__beneficio {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .ph-auth__beneficio-icono {
                display: flex;
                flex: none;
                align-items: center;
                justify-content: center;
                width: clamp(48px, 7.2vh, 70px);
                height: clamp(48px, 7.2vh, 70px);
                border: 1px solid rgba(168, 230, 163, 0.35);
                border-radius: 50%;
                background: rgba(0, 85, 72, 0.72);
                color: var(--ph-verde-claro);
            }

            .ph-auth__beneficio-icono svg {
                width: 48%;
                height: 48%;
            }

            .ph-auth__beneficio > div > strong {
                display: block;
                font-size: clamp(16px, 2.2vh, 21px);
                font-weight: 700;
                line-height: 1.2;
            }

            .ph-auth__beneficio > div > span {
                display: block;
                font-size: clamp(13.5px, 1.75vh, 17px);
                color: rgba(255, 255, 255, 0.8);
            }

            .ph-auth__stats {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                width: 100%;
                max-width: 690px;
                margin-top: auto;
                padding: clamp(14px, 2.2vh, 22px) clamp(18px, 2vw, 30px);
                border: 1px solid rgba(255, 255, 255, 0.12);
                border-radius: clamp(22px, 3.4vh, 34px);
                background: rgba(0, 72, 62, 0.62);
                backdrop-filter: blur(10px);
            }

            .ph-auth__stat + .ph-auth__stat {
                padding-left: clamp(14px, 1.6vw, 26px);
                border-left: 1px solid rgba(255, 255, 255, 0.22);
            }

            .ph-auth__stat-cifra {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .ph-auth__stat-cifra svg {
                flex: none;
                width: clamp(28px, 4.2vh, 42px);
                height: clamp(28px, 4.2vh, 42px);
                color: var(--ph-verde-claro);
            }

            .ph-auth__stat-cifra strong {
                font-size: clamp(28px, 4.3vh, 42px);
                font-weight: 700;
                font-variant-numeric: lining-nums;
                line-height: 1;
            }

            .ph-auth__stat-titulo {
                margin-top: 6px;
                font-size: clamp(14px, 1.9vh, 18px);
                font-weight: 700;
                line-height: 1.25;
            }

            .ph-auth__stat-detalle {
                font-size: clamp(12px, 1.55vh, 15px);
                line-height: 1.3;
                color: rgba(255, 255, 255, 0.8);
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
                            {{ __('Más que adopciones,') }}<br>
                            {{ __('son') }} <em>{{ __('nuevas historias') }}</em><br>
                            {{ __('por vivir.') }}
                        </h1>

                        <p class="ph-auth__descripcion">
                            {{ __('Conecta con fundaciones, adopta o apoya') }}<br>
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
