<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Crear Cuenta - PeliHuellas</title>

    <!-- Bulma CSS (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.2/css/bulma.min.css">

    <!-- Font Awesome (igual que en el resto del proyecto) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.css" integrity="sha512-x9WwyMYBnlXMNQ6kQ/Lyzu1NqIhLQKL5Oq6xByfXuRj7s9CskyCbLv/1IjqzJmXwFXWr0ov6jBV7Qbc0hh9nHg==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Tipografía serif para los títulos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap">

    <link rel="icon" href="/footprint_huella_logo.svg" sizes="any">
    <link rel="icon" href="/footprint_huella_logo.svg" type="image/svg+xml">

    <style>
        :root {
            --pf-green: #1f5c47;
            --pf-green-dark: #163f31;
            --pf-mint: #dcece4;
            --pf-panel-bg: #eef1ef;
            --pf-text-muted: #6b7280;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1b1b18;
        }

        h1, h2, h3, .pf-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }

        /* ---- Navbar ---- */
        .pf-navbar {
            border-bottom: 1px solid #ececec;
            padding: 0.5rem 1.5rem;
        }

        .pf-logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            background-color: var(--pf-green);
            color: #fff;
            border-radius: 8px;
            margin-right: 0.6rem;
            font-size: 0.95rem;
        }

        .pf-brand-text {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--pf-green-dark);
            font-size: 1.25rem;
        }

        .navbar-item, .navbar-link {
            font-weight: 500;
        }

        .btn-crear-cuenta-nav {
            background-color: var(--pf-green);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.5em 1.2em;
            font-weight: 600;
        }

        .btn-crear-cuenta-nav:hover {
            background-color: var(--pf-green-dark);
            color: #fff;
        }

        /* ---- Layout de dos columnas ---- */
        .pf-body {
            min-height: calc(100vh - 68px);
            margin: 0 !important;
        }

        /* ---- Columna del hero ---- */
        .pf-hero {
            position: relative;
            min-height: 320px;
            background-image:
                linear-gradient(to top, rgba(10, 25, 18, 0.85) 0%, rgba(10, 25, 18, 0.15) 55%, rgba(10, 25, 18, 0.05) 100%),
                url('https://images.unsplash.com/photo-1517849845537-4d257902861a?auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: #fff;
            display: flex;
            align-items: flex-end;
            padding: 2.5rem;
        }

        .pf-hero-content {
            width: 100%;
        }

        .pf-hero-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.2rem;
        }

        .pf-hero-brand .pf-logo-badge {
            background-color: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(2px);
        }

        .pf-hero-brand span {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.15rem;
        }

        .pf-hero-title {
            font-weight: 700;
            font-size: 2rem;
            line-height: 1.25;
            max-width: 26rem;
            margin-bottom: 0.9rem;
        }

        .pf-hero-subtitle {
            color: rgba(255, 255, 255, 0.85);
            max-width: 26rem;
            margin-bottom: 2rem;
        }

        .pf-hero-stats {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
        }

        .pf-hero-stats strong {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            display: block;
        }

        .pf-hero-stats span {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.85rem;
        }

        /* ---- Columna del formulario ---- */
        .pf-form-column {
            background-color: var(--pf-panel-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .pf-panel {
            width: 100%;
            max-width: 30rem;
        }

        .pf-panel h1 { 
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.25rem;
        }

        .pf-panel .subtitle {
            color: var(--pf-text-muted);
            font-size: 1rem;
            margin-bottom: 1.75rem;
        }

        .pf-panel label {
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.35rem;
            display: inline-block;
        }

        .pf-panel .input {
            border-radius: 10px;
            border-color: #e2e5e3;
            background-color: #fff;
            box-shadow: none;
            height: 2.75rem;
        }

        .pf-panel .input::placeholder {
            color: #a8aca9;
        }

        .pf-panel .input:focus {
            border-color: var(--pf-green);
            box-shadow: 0 0 0 1px var(--pf-green);
        }

        .pf-hint {
            color: var(--pf-text-muted);
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }

        .toggle-visibility {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--pf-text-muted);
            pointer-events: all;
        }

        .pf-terms {
            font-size: 0.9rem;
            color: #4b5045;
        }

        .pf-terms a {
            color: var(--pf-green);
            font-weight: 600;
        }

        .btn-submit {
            width: 100%;
            background-color: var(--pf-green);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            height: 2.9rem;
        }

        .btn-submit:hover {
            background-color: var(--pf-green-dark);
            color: #fff;
        }

        .pf-info-box {
            background-color: var(--pf-mint);
            color: #234a3a;
            border-radius: 10px;
            padding: 0.9rem 1rem;
            font-size: 0.85rem;
            margin-top: 1.25rem;
        }

        .pf-login-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.9rem;
        }

        .pf-login-link a {
            color: var(--pf-green);
            font-weight: 600;
        }

        .pf-back-link {
            display: block;
            text-align: center;
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: var(--pf-text-muted);
        }

        @media (max-width: 768px) {
            .pf-hero {
                min-height: 260px;
            }

            .pf-hero-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar pf-navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{ route('home') }}">
                <span class="pf-logo-badge"><i class="fa-solid fa-paw"></i></span>
                <span class="pf-brand-text">PeliHuellas</span>
            </a>

            <a role="button" class="navbar-burger" data-target="pfNavMenu" aria-label="menu" aria-expanded="false">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="pfNavMenu" class="navbar-menu">
            <div class="navbar-start ml-4">
                <a class="navbar-item" href="#">Adoptar</a>
                <a class="navbar-item" href="#">Servicios</a>
                <a class="navbar-item" href="#">Solicitudes</a>
                <a class="navbar-item" href="#">Mi perfil</a>
                <a class="navbar-item" href="#">Mis citas</a>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <a href="{{ Route::has('login') ? route('login') : '#' }}" class="button is-white">Ingresar</a>
                        <a href="{{ route('registro') }}" class="button btn-crear-cuenta-nav">Crear cuenta</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="columns pf-body is-mobile-reverse">
        <div class="column is-6 pf-hero is-hidden-mobile">
            <div class="pf-hero-content">
                <div class="pf-hero-brand">
                    <span class="pf-logo-badge"><i class="fa-solid fa-paw"></i></span>
                    <span>PeliHuellas</span>
                </div>

                <h2 class="pf-hero-title">Cada animal merece un hogar. Cada hogar, el animal correcto.</h2>
                <p class="pf-hero-subtitle">Fundaciones y veterinarias verificadas. Adopciones y cuidado con confianza.</p>

                <div class="pf-hero-stats">
                    <div>
                        <strong>3.841</strong>
                        <span>Animales</span>
                    </div>
                    <div>
                        <strong>127</strong>
                        <span>Fundaciones</span>
                    </div>
                    <div>
                        <strong>89</strong>
                        <span>Veterinarias</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-6 pf-form-column">
            <div class="pf-panel">
                <h1>Crear cuenta</h1>
                <p class="subtitle">Únete a PeliHuellas. Es gratis.</p>

                <form method="POST" action="{{ route('registro') }}" onsubmit="return false;">
                    @csrf

                    <div class="columns is-mobile">
                        <div class="column">
                            <div class="field">
                                <label for="nombres">Nombres</label>
                                <div class="control">
                                    <input class="input" type="text" id="nombres" name="nombres" placeholder="María" required>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label for="apellidos">Apellidos</label>
                                <div class="control">
                                    <input class="input" type="text" id="apellidos" name="apellidos" placeholder="García" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="correo">Correo electrónico</label>
                        <div class="control">
                            <input class="input" type="email" id="correo" name="correo" placeholder="tu@correo.com" required>
                        </div>
                    </div>

                    <div class="field">
                        <label for="telefono">Teléfono</label>
                        <div class="control">
                            <input class="input" type="tel" id="telefono" name="telefono" placeholder="310 555 0000">
                        </div>
                        <p class="pf-hint">Opcional — lo usan las fundaciones para contactarte.</p>
                    </div>

                    <div class="field">
                        <label for="password">Contraseña</label>
                        <div class="control has-icons-right">
                            <input class="input" type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" minlength="8" required>
                            <span class="icon is-right">
                                <button type="button" class="toggle-visibility" onclick="togglePassword('password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label for="confirm-password">Confirmar contraseña</label>
                        <div class="control has-icons-right">
                            <input class="input" type="password" id="confirm-password" name="confirm_password" placeholder="Repetí la contraseña" minlength="8" required>
                            <span class="icon is-right">
                                <button type="button" class="toggle-visibility" onclick="togglePassword('confirm-password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="checkbox pf-terms">
                            <input type="checkbox" id="terms" name="terms" required>
                            Acepto los <a href="#">Términos de uso</a> y la <a href="#">Política de privacidad</a>.
                        </label>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button btn-submit">Crear cuenta</button>
                        </div>
                    </div>
                </form>

                <div class="pf-info-box">
                    Con tu cuenta ya podés reservar citas de cuidado. Para solicitar una adopción te pediremos algunos datos más.
                </div>

                <div class="pf-login-link">
                    ¿Ya tenés cuenta? <a href="{{ Route::has('login') ? route('login') : '#' }}">Ingresar</a>
                </div>

                <a href="{{ route('home') }}" class="pf-back-link">&larr; Volver al catálogo</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isHidden);
            icon.classList.toggle('fa-eye-slash', isHidden);
        }

        // Burger menu (comportamiento estándar de Bulma)
        document.addEventListener('DOMContentLoaded', () => {
            const burger = document.querySelector('.navbar-burger');
            const menu = document.getElementById(burger?.dataset.target);
            burger?.addEventListener('click', () => {
                burger.classList.toggle('is-active');
                menu?.classList.toggle('is-active');
            });
        });
    </script>
</body>
</html>
