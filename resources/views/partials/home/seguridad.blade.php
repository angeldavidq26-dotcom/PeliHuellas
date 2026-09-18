@php
    $phSeguridadImagenes = [
        'images/cGVycm9iYW5uZXIx.jpg',
        'images/YmFubmVyZ2F0bzE=.jpg',
        'images/Z2F0b3lwZXJyb0I=.jpg',
        'images/cGVycm9iYW5uZXIy.jpg',
        'images/YmFubmVyZ2F0b3Yy.jpg',
        'images/Z2F0b3lwZXJyb0Iy.jpg',
        'images/cGVycm9iYW5uZXIz.jpg',
        'images/Z2F0b2Jhbm5lckcx.jpg',
        'images/Z2F0b3lwZXJyb0Iz.jpg',
        'images/cGVycm9iYW5uZXI0.jpg',
        'images/Z2F0b3lwZXJyb0I0.jpg',
        'images/bWFuYWRhYmFubmVyMQ==.jpg',
    ];

    $phSeguridadPuntos = [
        __('Certificado de existencia y representación legal'),
        __('Verificación manual por el equipo de PeliHuellas'),
        __('Sello visible en cada perfil verificado'),
    ];
@endphp

<style>
    .ph-seguridad {
        width: 100%;
        background: #f4f5f4;
        font-family: 'Instrument Sans', Inter, Montserrat, Roboto, ui-sans-serif, system-ui, sans-serif;
    }

    .ph-seguridad__inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
        align-items: stretch;
        min-height: 450px;
        width: 100%;
        margin: 0;
        gap: 0;
    }

    .ph-seguridad__contenido {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 64px clamp(32px, 4vw, 64px) 64px clamp(24px, 6vw, 112px);
    }

    .ph-seguridad__badge {
        align-self: flex-start;
        display: inline-block;
        margin-bottom: 18px;
        padding: 6px 14px;
        border-radius: 16px;
        background: #1B5C4F;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .ph-seguridad__titulo {
        margin: 0 0 16px;
        max-width: 20ch;
        font-size: 38px;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.01em;
        color: #111827;
    }

    .ph-seguridad__descripcion {
        margin: 0 0 26px;
        max-width: 38rem;
        font-size: 16px;
        line-height: 1.65;
        color: #6b7280;
    }

    .ph-seguridad__lista {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ph-seguridad__item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 15px;
        color: #111827;
    }

    .ph-seguridad__check {
        flex: 0 0 auto;
        width: 22px;
        height: 22px;
        color: #1B5C4F;
    }

    .ph-seguridad__media {
        position: relative;
        min-height: 450px;
        padding: 40px clamp(24px, 4vw, 56px);
    }

    .ph-seguridad__carrusel {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 370px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 30px 60px -24px rgba(27, 92, 79, 0.55);
    }

    .ph-seguridad__slide {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.6s ease-in-out;
    }

    .ph-seguridad__slide.is-active {
        opacity: 1;
    }

    @media (max-width: 1024px) {
        .ph-seguridad__inner {
            grid-template-columns: 1fr;
        }

        .ph-seguridad__contenido {
            padding: 48px 32px 36px;
        }

        .ph-seguridad__titulo {
            font-size: 30px;
        }

        .ph-seguridad__media {
            min-height: 360px;
            padding: 0 32px 48px;
        }

        .ph-seguridad__carrusel {
            min-height: 320px;
        }
    }

    @media (max-width: 640px) {
        .ph-seguridad__contenido {
            padding: 36px 20px 24px;
        }

        .ph-seguridad__titulo {
            font-size: 26px;
        }

        .ph-seguridad__descripcion {
            font-size: 15px;
        }

        .ph-seguridad__media {
            min-height: 280px;
            padding: 0 20px 36px;
        }

        .ph-seguridad__carrusel {
            min-height: 240px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .ph-seguridad__slide {
            transition: none;
        }
    }
</style>

<section class="ph-seguridad">
    <div class="ph-seguridad__inner">
        <div class="ph-seguridad__contenido">
            <span class="ph-seguridad__badge">{{ __('Seguridad') }}</span>

            <h2 class="ph-seguridad__titulo">
                {{ __('Todas las organizaciones pasan por verificación documental') }}
            </h2>

            <p class="ph-seguridad__descripcion">
                {{ __('Antes de publicar un animal o un servicio, cada fundación y veterinaria debe presentar su certificado de existencia y el documento de su representante legal. Revisamos todo manualmente. No hay atajos.') }}
            </p>

            <ul class="ph-seguridad__lista">
                @foreach ($phSeguridadPuntos as $punto)
                    <li class="ph-seguridad__item">
                        <svg class="ph-seguridad__check" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.415l-7.09 7.09a1 1 0 01-1.415 0L4.296 9.89a1 1 0 111.415-1.414l3.09 3.09 6.383-6.383a1 1 0 011.42.107z" clip-rule="evenodd" />
                        </svg>
                        {{ $punto }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="ph-seguridad__media">
            <div class="ph-seguridad__carrusel" data-ph-seguridad-carrusel role="img"
                 aria-label="{{ __('Gatos y perros en adopción en PeliHuellas') }}">
                @foreach ($phSeguridadImagenes as $indice => $imagen)
                    <img
                        src="{{ asset($imagen) }}"
                        alt=""
                        aria-hidden="true"
                        class="ph-seguridad__slide{{ $indice === 0 ? ' is-active' : '' }}"
                        loading="{{ $indice === 0 ? 'eager' : 'lazy' }}"
                    >
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        var carrusel = document.querySelector('[data-ph-seguridad-carrusel]');

        if (!carrusel) {
            return;
        }

        var slides = carrusel.querySelectorAll('.ph-seguridad__slide');

        if (slides.length < 2) {
            return;
        }

        var actual = 0;

        setInterval(function () {
            slides[actual].classList.remove('is-active');
            actual = (actual + 1) % slides.length;
            slides[actual].classList.add('is-active');
        }, 4000);
    })();
</script>
