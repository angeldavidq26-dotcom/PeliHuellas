# 📖 Guía Completa de Comandos Artisan para Laravel

Esta documentación detalla los comandos Artisan más utilizados y recomendados para este proyecto, organizados por categoría para facilitar su consulta y aplicación rápida.

---

## 📑 Tabla de Contenidos

1. [⚙️ Entorno y Servidor Local](#1-entorno-y-servidor-local)
2. [📦 Creación de Componentes Individuales (`make:`)](#2-creación-de-componentes-individuales-make)
   - [Modelos](#modelos)
   - [Migraciones](#migraciones)
   - [Controladores](#controladores)
   - [Form Requests (Validaciones)](#form-requests-validaciones)
   - [Vistas y Componentes](#vistas-y-componentes)
   - [Seeders y Factories (Poblado de Datos)](#seeders-y-factories-poblado-de-datos)
   - [Middlewares](#middlewares)
   - [Policies y Rules](#policies-y-rules)
3. [🗄️ Base de Datos y Migraciones](#3-base-de-datos-y-migraciones)
4. [🌐 Gestión de Rutas](#4-gestión-de-rutas)
5. [🧹 Caché y Optimización](#5-caché-y-optimización)
6. [🚀 Guía Paso a Paso: Cómo Crear un Módulo Completo](#6-guía-paso-a-paso-cómo-crear-un-módulo-completo)
7. [📑 Chuleta Rápida de Banderas (Flags)](#7-chuleta-rápida-de-banderas-flags)
8. [📦 Composer (Gestor de Dependencias PHP)](#8-composer-gestor-de-dependencias-php)
9. [🎨 NPM y Vite (Assets del Frontend)](#9-npm-y-vite-assets-del-frontend)
10. [🧪 Testing y Calidad de Código](#10-testing-y-calidad-de-código)
11. [📬 Colas (Queues) y Trabajos en Segundo Plano](#11-colas-queues-y-trabajos-en-segundo-plano)
12. [⏰ Tareas Programadas (Scheduler)](#12-tareas-programadas-scheduler)
13. [🧰 Tinker en Profundidad](#13-tinker-en-profundidad)
14. [🐳 Laravel Sail (Entorno Docker, opcional)](#14-laravel-sail-entorno-docker-opcional)
15. [🐾 Guía Paso a Paso extra: CRUD con Livewire](#15-guía-paso-a-paso-extra-crud-con-livewire)
16. [⚠️ Comandos que Mejor NO Utilizar](#16-comandos-que-mejor-no-utilizar)

---

## 1. ⚙️ Entorno y Servidor Local

| Comando | Descripción |
| :--- | :--- |
| `php artisan serve` | Inicia el servidor de desarrollo local en `http://127.0.0.1:8000`. |
| `composer run dev` | Inicia el entorno de desarrollo completo (servidor + Vite/Livewire). |
| `php artisan tinker` | Abre una consola interactiva para interactuar con la BD, modelos y código PHP. |
| `php artisan list` | Lista todos los comandos disponibles en el proyecto. |
| `php artisan help <comando>` | Muestra la ayuda y opciones de un comando específico (ej: `php artisan help make:model`). |
| `php artisan key:generate` | Genera o regenera la clave de aplicación `APP_KEY` en `.env`. |
| `php artisan storage:link` | Crea el enlace simbólico de `public/storage` a `storage/app/public` para archivos subidos. |

---

## 2. 📦 Creación de Componentes Individuales (`make:`)

### Modelos
Crea modelos Eloquent dentro de `app/Models/`.

```bash
# Modelo simple
php artisan make:model Laptop

# Modelo con migración (-m)
php artisan make:model Laptop -m

# Modelo con migración, controlador resource y factory
php artisan make:model Laptop -mcrf

# Modelo completo (Migración, Factory, Seeder, Controlador, Requests, Policy)
php artisan make:model Laptop -a --requests
```

---

### Migraciones
Crea archivos de migración en `database/migrations/`.

```bash
# Migración para CREAR una nueva tabla (sigue la convención create_nombre_table)
php artisan make:migration create_laptops_table

# Migración para MODIFICAR una tabla existente (agrega columnas, llaves foráneas, etc.)
php artisan make:migration add_status_to_laptops_table --table=laptops
```

---

### Controladores
Crea controladores en `app/Http/Controllers/`.

```bash
# Controlador básico vacío
php artisan make:controller LaptopController

# Controlador con métodos CRUD predefinidos (Resource: index, create, store, show, edit, update, destroy)
php artisan make:controller LaptopController --resource

# Controlador Resource vinculado directamente a un Modelo (Inyección de dependencias)
php artisan make:controller LaptopController --resource --model=Laptop

# Controlador con Form Requests generados automáticamente
php artisan make:controller LaptopController --resource --model=Laptop --requests

# Controlador para API (CRUD sin vistas create ni edit)
php artisan make:controller Api/LaptopController --api

# Controlador de acción única (Invokable)
php artisan make:controller ReportePrestamoController --invokable
```

---

### Form Requests (Validaciones)
Separa la lógica de validación del controlador en `app/Http/Requests/`.

```bash
# Request para creación / guardado
php artisan make:request StoreLaptopRequest

# Request para actualización / edición
php artisan make:request UpdateLaptopRequest
```

---

### Vistas y Componentes

```bash
# Crear un archivo de vista Blade (crea resources/views/laptops/index.blade.php)
php artisan make:view laptops.index

# Crear un componente Blade (clase en app/View/Components y vista en resources/views/components)
php artisan make:component Modal

# Crear un componente anónimo Blade (solo vista en resources/views/components)
php artisan make:component modal --view

# Crear un componente Livewire (crea clase PHP y vista Blade)
php artisan make:livewire LaptopTable
# o alternativamente:
php artisan livewire:make LaptopTable
```

---

### Seeders y Factories (Poblado de Datos)

```bash
# Crear un Factory para generar datos falsos de prueba con Faker (database/factories/)
php artisan make:factory LaptopFactory --model=Laptop

# Crear un Seeder para insertar datos en la BD (database/seeders/)
php artisan make:seeder LaptopSeeder
```

---

### Middlewares
Crea filtros de peticiones HTTP en `app/Http/Middleware/`.

```bash
# Crear un middleware
php artisan make:middleware CheckUserRole
```

---

### Policies y Rules

```bash
# Crear una Política de autorización vinculada a un Modelo (app/Policies/)
php artisan make:policy LaptopPolicy --model=Laptop

# Crear una Regla de validación personalizada (app/Rules/)
php artisan make:rule ValidarSerialLaptop
```

---

## 3. 🗄️ Base de Datos y Migraciones

| Comando | Descripción |
| :--- | :--- |
| `php artisan migrate` | Ejecuta las migraciones pendientes. |
| `php artisan migrate:status` | Muestra el estado de cada migración (si fue ejecutada o está pendiente). |
| `php artisan migrate:rollback` | Revierte el último lote de migraciones ejecutadas. |
| `php artisan migrate:rollback --step=1` | Revierte exactamente la última migración. |
| `php artisan migrate:reset` | Revierte **todas** las migraciones de la base de datos. |
| `php artisan migrate:refresh` | Revierte todas las migraciones y las vuelve a ejecutar. |
| `php artisan migrate:refresh --seed` | Revierte, ejecuta migraciones y vuelve a poblar la BD con seeders. |
| `php artisan migrate:fresh` | **Elimina todas las tablas** y ejecuta las migraciones desde cero. *(¡Usar con cuidado en desarrollo!)* |
| `php artisan migrate:fresh --seed` | Borra todo, migra desde cero y ejecuta los seeders (ideal para reiniciar la BD). |
| `php artisan db:seed` | Ejecuta el seeder principal (`DatabaseSeeder`). |
| `php artisan db:seed --class=LaptopSeeder` | Ejecuta un Seeder específico. |

---

## 4. 🌐 Gestión de Rutas

```bash
# Ver todas las rutas registradas en la aplicación
php artisan route:list

# Filtrar rutas por nombre o URI
php artisan route:list --path=laptops
php artisan route:list --name=laptops

# Ocultar rutas de paquetes de terceros
php artisan route:list --except-vendor

# Cachear rutas (Optimización para producción)
php artisan route:cache

# Limpiar la caché de rutas
php artisan route:clear
```

### ¿Cómo registrar rutas en `routes/web.php`?

```php
use App\Http\Controllers\LaptopController;
use Illuminate\Support\Facades\Route;

// Ruta individual
Route::get('/laptops', [LaptopController::class, 'index'])->name('laptops.index');

// Rutas CRUD completas automáticas (7 rutas estándar)
Route::resource('laptops', LaptopController::class);

// Rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {
    Route::resource('laptops', LaptopController::class);
});
```

---

## 5. 🧹 Caché y Optimización

Cuando hagas cambios en configuraciones (`.env`), rutas o eventos que no se reflejen, ejecuta estos comandos:

```bash
# LIMPIEZA TOTAL (Limpia caché de configuración, rutas, vistas y eventos)
php artisan optimize:clear

# O individualmente:
php artisan config:clear    # Limpia caché de configuración (.env)
php artisan route:clear     # Limpia caché de rutas
php artisan view:clear      # Limpia vistas Blade compiladas
php artisan cache:clear     # Limpia el cache general de la app

# MODO PRODUCCIÓN (Genera cachés de alto rendimiento)
php artisan optimize        # Cachea configuración, rutas y vistas a la vez
php artisan config:cache
php artisan route:cache
php artisan view:cache

# MODO MANTENIMIENTO
php artisan down            # Pone la aplicación en modo mantenimiento
php artisan up              # Vuelve a activar la aplicación
```

---

## 6. 🚀 Guía Paso a Paso: Cómo Crear un Módulo Completo

A continuación se muestra cómo construir un módulo desde cero utilizando como ejemplo un módulo de **`Laptop`** (Portátiles).

---

### Opción A: Modo Rápido (Comando Todo en Uno) ⚡

Ejecuta el siguiente comando para generar el modelo junto con su migración, controlador tipo resource, form requests, seeder, factory y policy:

```bash
php artisan make:model Laptop -a --requests
```

Esto generará automáticamente:
1. `app/Models/Laptop.php` (Modelo)
2. `database/migrations/xxxx_xx_xx_xxxxxx_create_laptops_table.php` (Migración)
3. `database/factories/LaptopFactory.php` (Factory)
4. `database/seeders/LaptopSeeder.php` (Seeder)
5. `app/Http/Requests/StoreLaptopRequest.php` (Validación Crear)
6. `app/Http/Requests/UpdateLaptopRequest.php` (Validación Editar)
7. `app/Http/Controllers/LaptopController.php` (Controlador Resource)
8. `app/Policies/LaptopPolicy.php` (Políticas de acceso)

---

### Opción B: Paso a Paso Estructurado y Modular 🛠️

Si prefieres tener control absoluto paso por paso:

#### Paso 1: Crear Modelo y Migración
```bash
php artisan make:model Laptop -m
```

#### Paso 2: Definir la estructura en la Migración
Abre el archivo generado en `database/migrations/xxxx_create_laptops_table.php` y define las columnas:

```php
public function up(): void
{
    Schema::create('laptops', function (Blueprint $table) {
        $table->id();
        $table->string('serial')->unique();
        $table->string('marca');
        $table->string('modelo');
        $table->enum('estado', ['disponible', 'prestado', 'mantenimiento'])->default('disponible');
        $table->text('observaciones')->nullable();
        $table->timestamps();
    });
}
```

Aplica la migración a la base de datos:
```bash
php artisan migrate
```

#### Paso 3: Configurar el Modelo
Edita `app/Models/Laptop.php` para habilitar la asignación masiva (`$fillable`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial',
        'marca',
        'modelo',
        'estado',
        'observaciones',
    ];
}
```

#### Paso 4: Crear los Form Requests (Validaciones)
```bash
php artisan make:request StoreLaptopRequest
php artisan make:request UpdateLaptopRequest
```

En `app/Http/Requests/StoreLaptopRequest.php`:
```php
public function authorize(): bool
{
    return true; // Cambiar a true si el usuario tiene permiso
}

public function rules(): array
{
    return [
        'serial' => 'required|string|unique:laptops,serial',
        'marca' => 'required|string|max:100',
        'modelo' => 'required|string|max:100',
        'estado' => 'required|in:disponible,prestado,mantenimiento',
        'observaciones' => 'nullable|string',
    ];
}
```

#### Paso 5: Crear el Controlador Resource
```bash
php artisan make:controller LaptopController --resource --model=Laptop
```

Edita los métodos del controlador `app/Http/Controllers/LaptopController.php` conectando los Requests y las Vistas:

```php
namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Http\Requests\StoreLaptopRequest;
use App\Http\Requests\UpdateLaptopRequest;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index()
    {
        $laptops = Laptop::latest()->paginate(10);
        return view('laptops.index', compact('laptops'));
    }

    public function create()
    {
        return view('laptops.create');
    }

    public function store(StoreLaptopRequest $request)
    {
        Laptop::create($request->validated());
        return redirect()->route('laptops.index')->with('success', 'Portátil registrado correctamente.');
    }

    public function show(Laptop $laptop)
    {
        return view('laptops.show', compact('laptop'));
    }

    public function edit(Laptop $laptop)
    {
        return view('laptops.edit', compact('laptop'));
    }

    public function update(UpdateLaptopRequest $request, Laptop $laptop)
    {
        $laptop->update($request->validated());
        return redirect()->route('laptops.index')->with('success', 'Portátil actualizado correctamente.');
    }

    public function destroy(Laptop $laptop)
    {
        $laptop->delete();
        return redirect()->route('laptops.index')->with('success', 'Portátil eliminado correctamente.');
    }
}
```

#### Paso 6: Crear las Vistas Blade
Crea las vistas necesarias para el CRUD:

```bash
php artisan make:view laptops.index
php artisan make:view laptops.create
php artisan make:view laptops.edit
php artisan make:view laptops.show
```

*(O si utilizas componentes Livewire en lugar de vistas tradicionales Blade, puedes crearlos con `php artisan make:livewire Laptops/Index`)*.

#### Paso 7: Registrar las Rutas
Abre `routes/web.php` y añade el recurso:

```php
use App\Http\Controllers\LaptopController;

Route::middleware(['auth'])->group(function () {
    Route::resource('laptops', LaptopController::class);
});
```

#### Paso 8: Factory y Seeder para Datos de Prueba (Opcional pero Recomendado)
```bash
php artisan make:factory LaptopFactory --model=Laptop
php artisan make:seeder LaptopSeeder
```

En `database/factories/LaptopFactory.php`:
```php
public function definition(): array
{
    return [
        'serial' => fake()->unique()->bothify('LP-####-????'),
        'marca' => fake()->randomElement(['Dell', 'HP', 'Lenovo', 'Asus']),
        'modelo' => fake()->word(),
        'estado' => fake()->randomElement(['disponible', 'prestado', 'mantenimiento']),
        'observaciones' => fake()->sentence(),
    ];
}
```

En `database/seeders/LaptopSeeder.php`:
```php
public function run(): void
{
    \App\Models\Laptop::factory(20)->create();
}
```

Ejecuta el seeder:
```bash
php artisan db:seed --class=LaptopSeeder
```

#### Paso 9: Validar Rutas
Comprueba que todas las rutas del módulo se han registrado correctamente:
```bash
php artisan route:list --name=laptops
```

---

## 7. 📑 Chuleta Rápida de Banderas (Flags)

Al usar `php artisan make:model <Nombre>`, puedes combinar las siguientes banderas:

| Bandera | Significado | Qué genera |
| :--- | :--- | :--- |
| `-m` | `--migration` | Crea el archivo de migración de base de datos. |
| `-c` | `--controller` | Crea el controlador. |
| `-r` | `--resource` | Hace que el controlador incluya métodos CRUD. |
| `-f` | `--factory` | Crea el Factory para datos faker. |
| `-s` | `--seed` | Crea el Seeder para poblar la tabla. |
| `-p` | `--pivot` | Indica que es un modelo de tabla intermedia (Pivot). |
| `-R` | `--requests` | Crea las clases `FormRequest` (Store y Update). |
| `--policy` | `--policy` | Crea la clase Policy de autorización. |
| `-a` | `--all` | Genera **Migración, Factory, Seeder, Policy, Resource Controller y Form Requests**. |

---

## 8. 📦 Composer (Gestor de Dependencias PHP)

Composer no es Artisan, es el gestor de paquetes de PHP. Se ejecuta directamente en bash (sin `php artisan` delante) y es el que instala Laravel, Livewire, Flux, etc.

| Comando | Descripción | ¿Cuándo usarlo? |
| :--- | :--- | :--- |
| `composer install` | Instala **exactamente** las versiones fijadas en `composer.lock`. | Al clonar el proyecto, en CI, en producción. Es reproducible: todo el equipo tiene las mismas versiones. |
| `composer update` | Busca las versiones más nuevas que cumplan `composer.json` y **reescribe** `composer.lock`. | Solo cuando quieras subir versiones deliberadamente, en local, con tests después. |
| `composer update laravel/framework` | Actualiza solo ese paquete concreto. | Para subir una dependencia sin tocar el resto. |
| `composer require paquete/nombre` | Añade una dependencia nueva de producción, la instala y actualiza el lock. | Al incorporar una librería nueva al proyecto. |
| `composer require --dev paquete/nombre` | Igual, pero como dependencia de desarrollo (tests, análisis, etc.). | Herramientas como Pint, PHPStan, Pest. |
| `composer remove paquete/nombre` | Desinstala una dependencia. | Cuando dejas de usar una librería. |
| `composer dump-autoload` | Regenera el mapa de autoload de clases. | Tras crear clases manualmente fuera de las convenciones de `make:`, o si el autoload "no encuentra" una clase que sí existe. |
| `composer dump-autoload -o` | Igual, pero optimizado (mapa de clases en lugar de reglas PSR-4). | En producción, mejora el rendimiento de carga de clases. |
| `composer show` | Lista los paquetes instalados y su versión. | Para saber qué versión de algo tienes instalada. |
| `composer outdated` | Muestra qué paquetes tienen versiones más nuevas disponibles. | Auditoría periódica de dependencias. |
| `composer validate` | Comprueba que `composer.json` está bien formado. | Antes de un commit si tocaste ese archivo a mano. |
| `composer run <script>` | Ejecuta un script definido en la clave `"scripts"` de `composer.json`. | Ver más abajo: este proyecto ya trae varios preconfigurados. |

### Scripts propios de este proyecto (`composer.json`)

Este repo ya define atajos útiles que conviene usar en lugar de recordar comandos sueltos:

```bash
composer run setup       # composer install + copia .env + key:generate + migrate + npm install + npm run build (primer arranque del proyecto)
composer run dev         # arranca "php artisan dev": servidor + cola + logs (pail) + Vite, todo a la vez, en un solo terminal
composer run lint        # aplica el formateo de código con Laravel Pint (lo reescribe)
composer run lint:check  # solo comprueba el estilo, sin modificar nada (ideal para CI)
composer run types:check # análisis estático con PHPStan/Larastan
composer run test        # limpia config, comprueba estilo, tipos y ejecuta los tests: el pipeline completo
composer run ci:check    # el mismo pipeline pensado para integración continua
```

---

## 9. 🎨 NPM y Vite (Assets del Frontend)

Laravel usa **Vite** para compilar CSS/JS (Tailwind en este proyecto). Estos comandos van con `npm`, no con `php artisan`.

| Comando | Descripción | ¿Cuándo usarlo? |
| :--- | :--- | :--- |
| `npm install` | Instala las dependencias de `package.json`/`package-lock.json` en `node_modules`. | Al clonar el proyecto o tras cambiar dependencias JS. |
| `npm ci` | Igual que `install`, pero exige que `package-lock.json` sea exacto y borra `node_modules` antes. | En CI/producción, más estricto y reproducible que `npm install`. |
| `npm run dev` | Arranca el servidor de Vite con **Hot Module Replacement** (recarga en caliente al guardar). | Mientras desarrollas: ves los cambios de CSS/JS al instante sin recargar la página a mano. |
| `npm run build` | Compila y minifica los assets para producción (los deja en `public/build`). | Antes de desplegar; sin esto, la app en producción no tiene los CSS/JS compilados. |

> 💡 En este proyecto casi nunca necesitas lanzar `npm run dev` y `php artisan serve` por separado: usa `composer run dev`, que arranca todo (servidor, cola, logs y Vite) en un único comando.

---

## 10. 🧪 Testing y Calidad de Código

| Comando | Descripción |
| :--- | :--- |
| `php artisan test` | Ejecuta la suite de tests (PHPUnit/Pest) con una salida más legible que el binario directo. |
| `php artisan test --filter=NombreDelTest` | Ejecuta solo el test (o los tests) cuyo nombre coincide. |
| `php artisan test --parallel` | Ejecuta los tests en paralelo, más rápido en proyectos grandes. |
| `./vendor/bin/pint` | Formatea el código PHP siguiendo un estándar de estilo (PSR-12 + reglas de Laravel), reescribiendo archivos. |
| `./vendor/bin/pint --test` | Solo verifica el estilo, sin modificar nada (falla si algo no cumple el estándar). |
| `./vendor/bin/phpstan analyse` | Análisis estático (Larastan) que detecta errores de tipos y bugs sin ejecutar el código. |

**¿Por qué importa esto?** Antes de hacer `git push`, correr `composer run test` (que encadena limpieza de config + Pint + PHPStan + tests) evita subir código que rompa la integración continua.

---

## 11. 📬 Colas (Queues) y Trabajos en Segundo Plano

Para tareas que no deben bloquear la respuesta al usuario (enviar emails, procesar imágenes, notificaciones de equipo, etc.).

| Comando | Descripción |
| :--- | :--- |
| `php artisan queue:work` | Arranca un "worker" que procesa trabajos en cola de forma continua. Es el que se usa en producción (junto a Supervisor). |
| `php artisan queue:listen` | Igual, pero recarga el código en cada trabajo automáticamente. Más lento, solo para desarrollo. |
| `php artisan queue:table` | Genera la migración para la tabla `jobs` (necesaria si usas el driver `database` para las colas). |
| `php artisan queue:failed-table` | Genera la migración para la tabla `failed_jobs`. |
| `php artisan queue:failed` | Lista los trabajos que fallaron. |
| `php artisan queue:retry <id>` / `queue:retry all` | Reintenta uno o todos los trabajos fallidos. |
| `php artisan queue:restart` | **Importante tras cada despliegue**: los workers cargan el código en memoria al iniciar, así que si subes código nuevo sin reiniciarlos, seguirán ejecutando la versión vieja. |

---

## 12. ⏰ Tareas Programadas (Scheduler)

Este proyecto ya usa el scheduler (mira `routes/console.php`, hay una tarea que borra invitaciones de equipo caducadas).

| Comando | Descripción |
| :--- | :--- |
| `php artisan schedule:list` | Muestra todas las tareas programadas y cuándo se ejecutan. |
| `php artisan schedule:run` | Ejecuta las tareas que tocan en este minuto. Es lo que un cron real llama cada minuto en producción (`* * * * * php artisan schedule:run`). |
| `php artisan schedule:work` | Simula el cron en local, ejecutándose en primer plano sin tener que configurar un cron de verdad. |
| `php artisan schedule:test` | Permite disparar manualmente una tarea programada concreta para probarla ya, sin esperar a su horario. |

---

## 13. 🧰 Tinker en Profundidad

`php artisan tinker` abre una consola PHP interactiva con toda la aplicación (modelos, facades, helpers) ya cargada. Ejemplos útiles:

```bash
php artisan tinker
```

```php
>>> App\Models\User::count()                      // Cuenta usuarios
>>> App\Models\User::first()                       // Trae el primer usuario
>>> App\Models\User::where('email', 'a@a.com')->first()
>>> App\Models\User::factory()->create()           // Crea un usuario de prueba con Faker
>>> config('app.env')                              // Lee un valor de configuración
>>> Auth::user()                                   // (solo tiene sentido en contexto de request real)
```

Es ideal para explorar datos y probar consultas Eloquent rápido, sin escribir un controlador ni una ruta solo para comprobar algo.

---

## 14. 🐳 Laravel Sail (Entorno Docker, opcional)

Este proyecto trae `laravel/sail` como dependencia de desarrollo. Es un entorno Docker (PHP, base de datos, etc.) listo para usar si no quieres instalar PHP/MySQL directamente en tu máquina.

| Comando | Descripción |
| :--- | :--- |
| `./vendor/bin/sail up -d` | Levanta los contenedores en segundo plano. |
| `./vendor/bin/sail down` | Detiene y elimina los contenedores. |
| `./vendor/bin/sail artisan migrate` | Ejecuta cualquier comando Artisan **dentro** del contenedor. |
| `./vendor/bin/sail composer require ...` | Igual, pero con Composer dentro del contenedor. |
| `./vendor/bin/sail npm run dev` | Igual, pero con npm dentro del contenedor. |
| `./vendor/bin/sail test` | Corre los tests dentro del contenedor. |

> Si ya tienes PHP y una base de datos funcionando en local (como en este entorno), no necesitas Sail: úsalo solo si prefieres trabajar 100% en Docker.

---

## 15. 🐾 Guía Paso a Paso extra: CRUD con Livewire

Este proyecto usa **Livewire + Flux**, así que además del flujo con Controladores/Blade de la sección 6, así es el flujo "nativo" con Livewire (sin controlador, sin form request separado: el propio componente valida y guarda). Ejemplo con un recurso `Mascota`:

```bash
# 1. Modelo + migración
php artisan make:model Mascota -m

# 2. (edita la migración: nombre, especie, raza, edad, estado, foto nullable...) y aplícala
php artisan migrate

# 3. Componente Livewire para listar (tabla/búsqueda/paginación)
php artisan make:livewire Mascotas/Index

# 4. Componente Livewire para el formulario de crear/editar
php artisan make:livewire Mascotas/Form

# 5. (opcional) Policy si quieres controlar quién puede editar/borrar
php artisan make:policy MascotaPolicy --model=Mascota
```

En `routes/web.php`, un componente Livewire se registra como ruta directamente (no hace falta controlador):

```php
use App\Livewire\Mascotas\Index as MascotasIndex;

Route::middleware(['auth'])->group(function () {
    Route::get('/mascotas', MascotasIndex::class)->name('mascotas.index');
});
```

```bash
# 6. Verifica que la ruta quedó registrada
php artisan route:list --name=mascotas
```

La diferencia clave con el flujo de Controlador: el componente Livewire (`app/Livewire/Mascotas/Index.php`) lleva la lógica **y** la vista (`resources/views/livewire/mascotas/index.blade.php`) juntas, y se actualiza en el navegador sin recargar la página.

---

## 16. ⚠️ Comandos que Mejor NO Utilizar

Comandos reales, que existen y funcionan, pero que causan pérdida de datos, bugs silenciosos o problemas de seguridad si no sabes exactamente lo que hacen. Como regla general: si el nombre incluye `fresh`, `reset`, `wipe`, `--force` o `--hard`, párate a pensar dos veces antes de darle a Enter.

| Comando | Por qué evitarlo |
| :--- | :--- |
| `php artisan migrate:fresh` (y `--seed`) | **Borra todas las tablas** de la base de datos y las vuelve a crear desde cero. Perfecto en local/testing, catastrófico en producción. |
| `php artisan migrate:reset` | Revierte **todas** las migraciones ejecutadas alguna vez. Misma familia de riesgo que `migrate:fresh`. |
| `php artisan db:wipe` | Borra literalmente todo (tablas, vistas, tipos) de la base de datos configurada, sin pedir confirmación extra. Nunca contra una BD real. |
| `php artisan queue:flush` | Borra el historial de trabajos fallidos. Pierdes la trazabilidad del error antes de haberlo diagnosticado. |
| `php artisan make:model X -a --force` (o cualquier `make:` con `--force` sobre un archivo ya editado) | `--force` sobreescribe el archivo existente **sin avisar**. Si ya habías tocado ese controlador/modelo, pierdes tus cambios. |
| `composer update` directo en producción | Puede traer versiones nuevas de dependencias que rompan cosas sin haber pasado por pruebas. En producción usa `composer install --no-dev --optimize-autoloader` (respeta el `composer.lock` ya probado). |
| `php artisan route:cache` con rutas que usan Closures | El caché de rutas no soporta funciones anónimas (`function () {...}` dentro de `Route::get`), solo referencias a controladores. Si las tienes, el comando falla o rompe la app cacheada. |
| Dejar `config:cache` activo tras editar `.env` sin volver a cachear | Con el caché de configuración activo, Laravel deja de leer `.env` directamente: tus cambios no se aplican hasta `config:clear` o repetir `config:cache`. |
| `APP_DEBUG=true` en producción | Expone trazas de error completas, rutas internas y variables de entorno a cualquier visitante. Riesgo de seguridad real. |
| `php artisan tinker` contra la base de datos de producción para "arreglar un dato a mano" | Sin transacción ni respaldo previo, un error de tipeo (`->delete()` en vez de `->first()`, por ejemplo) borra o corrompe datos reales de forma irreversible. |
| `git push --force` sobre `main` u otra rama compartida | Reescribe el historial remoto; si alguien más subió commits, los borra de un plumazo. Usa como mucho `--force-with-lease` y solo en tu propia rama. |
| `git reset --hard` sin haber hecho `git stash` o commit antes | Descarta cambios locales no comiteados de forma irreversible. |
| Subir el `.env` real al repositorio (`git add .env` / quitarlo del `.gitignore`) | Filtra contraseñas, claves de API y el `APP_KEY` de la aplicación. Comparte siempre `.env.example` en su lugar. |
| `rm -rf` a secas, sobre todo con rutas escritas a mano | Un espacio de más (`rm -rf vendor /`) puede borrar mucho más de lo que pretendías, y no hay papelera de reciclaje. |
