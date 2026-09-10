<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel Fundacion </title>
</head>
<boby>

<!-- ==========================================
     BARRA LATERAL (SIDEBAR)
     ========================================== -->
<aside class="sidebar">
    <!-- Encabezado del Menú (Logo y Nombre) -->
    <div class="sidebar-header">
        <div class="logo-icon">🏠</div> <!-- SVG en la vida real -->
        <div class="brand-info">
            <h2>Huellas Felices</h2>
            <span>Fundación</span>
        </div>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">
        <ul>
            <li class="active">
                <a href="#">🏠 Panel</a>
            </li>
            <li>
                <a href="#">🐾 Mis animales</a>
                <span class="badge">1</span>
            </li>
            <li>
                <a href="#">📄 Solicitudes</a>
                <span class="badge">5</span>
            </li>
            <li><a href="#">➕ Publicar animal</a></li>
            <li><a href="#">✅ Verificación</a></li>
            <li><a href="#">📍 Sedes</a></li>
            <li><a href="#">⚙️ Configuración</a></li>
        </ul>
    </nav>
</aside>


<!-- ==========================================
     CONTENIDO PRINCIPAL
     ========================================== -->
<main class="main-content">

    <!-- Encabezado de la página -->
    <header class="page-header">
        <div class="header-titles">
            <h1>Panel de la fundación</h1>
            <p>Huellas Felices · Bogotá</p>
        </div>
        <button class="btn-primary">+ Publicar animal</button>
    </header>

    <!-- Banner de Alerta -->
    <div class="alert-banner warning">
        <div class="alert-content">
            <span class="icon">⚠️</span>
            <p><strong>Tenés 1 animal esperando que actualices su estado</strong> — llevan más de 3 días en espera sin novedades.</p>
        </div>
        <a href="#" class="alert-link">Revisar ahora →</a>
    </div>

    <!-- Sección de Tarjetas de Estadísticas (Usar CSS Grid aquí) -->
    <section class="stats-grid">
        <!-- Tarjeta 1 -->
        <article class="stat-card">
            <div class="icon-wrapper green">📍</div>
            <h3>2</h3>
            <p>Animales disponibles</p>
        </article>
        <!-- Tarjeta 2 -->
        <article class="stat-card">
            <div class="icon-wrapper orange">🛡️</div>
            <h3>1</h3>
            <p>En espera</p>
        </article>
        <!-- Tarjeta 3 -->
        <article class="stat-card">
            <div class="icon-wrapper green">📄</div>
            <h3>5</h3>
            <p>Solicitudes sin revisar</p>
        </article>
        <!-- Tarjeta 4 -->
        <article class="stat-card">
            <div class="icon-wrapper green">❤️</div>
            <h3>3</h3>
            <p>Adopciones del mes</p>
        </article>
    </section>

    <!-- Sección de Tabla de Solicitudes -->
    <section class="recent-requests">
        <header class="section-header">
            <h2>Últimas solicitudes</h2>
            <a href="#">Ver todas →</a>
        </header>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ANIMAL</th>
                        <th>SOLICITANTE</th>
                        <th>FECHA</th>
                        <th>ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Zeus</td>
                        <td>Valentina Torres</td>
                        <td>19 ago</td>
                        <td><span class="status-badge new">Nueva</span></td>
                    </tr>
                    <tr>
                        <td>Zeus</td>
                        <td>Ricardo Mejía</td>
                        <td>20 ago</td>
                        <td><span class="status-badge new">Nueva</span></td>
                    </tr>
                    <!-- Repetir filas según sea necesario -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Sección de Mis Animales (Mini tarjetas) -->
    <section class="my-animals">
        <header class="section-header">
            <h2>Mis animales</h2>
        </header>

        <!-- Contenedor flex o grid para las fotos -->
        <div class="animal-cards-grid">
            <!-- Animal 1 -->
            <article class="animal-profile-card">
                <img src="ruta/zeus.jpg" alt="Foto de Zeus">
                <div class="animal-info">
                    <h4>Zeus</h4>
                    <p>Pitbull Americano</p>
                    <span class="tag tag-warning">En espera · 4d</span>
                </div>
            </article>

            <!-- Animal 2 -->
            <article class="animal-profile-card">
                <img src="ruta/cleo.jpg" alt="Foto de Cleo">
                <div class="animal-info">
                    <h4>Cleo</h4>
                    <p>Persa Himalayo</p>
                    <span class="tag tag-success">Disponible</span>
                </div>
            </article>

            <!-- Animal 3 -->
            <article class="animal-profile-card">
                <img src="ruta/bruno.jpg" alt="Foto de Bruno">
                <div class="animal-info">
                    <h4>Bruno</h4>
                    <p>Labrador Retriever</p>
                    <span class="tag tag-success">Disponible</span>
                </div>
            </article>
        </div>
    </section>

</main>

</body>
</html>
