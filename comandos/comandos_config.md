# ⚙️ Comandos de Configuración y Entorno (`.env`)

Complemento a la sección 5 de [`comandosLaravel.md`](../comandosLaravel.md) (caché y optimización), centrado específicamente en configuración y variables de entorno.

| Comando | Para qué sirve |
| :--- | :--- |
| `php artisan key:generate` | Genera la clave `APP_KEY` usada para encriptar sesiones, cookies y datos. Se ejecuta una sola vez al instalar el proyecto (ya lo hace `composer run setup`). |
| `php artisan config:clear` | Borra la caché de configuración. Úsalo **siempre** después de editar `.env` si algo no se aplica. |
| `php artisan config:cache` | Combina todos los archivos de `config/*.php` (con los valores de `.env` ya resueltos) en un único archivo cacheado, para máximo rendimiento. **Solo en producción**: mientras esté activo, Laravel deja de leer `.env` directamente. |
| `php artisan config:show database` | Muestra la configuración final ya resuelta de un archivo de config concreto (ej. `config/database.php`). Útil para depurar qué valores está usando realmente la app en ese momento. |
| `php artisan about` | Resumen del estado de la app: versión de Laravel/PHP, entorno (`local`/`production`), drivers de caché/cola/sesión, si el debug está activo, etc. El primer comando a lanzar cuando algo "no cuadra". |
| `php artisan env:encrypt` | Encripta el archivo `.env` completo (genera `.env.encrypted`) para poder compartirlo o versionarlo de forma segura. |
| `php artisan env:decrypt` | Desencripta un `.env.encrypted` de vuelta a `.env`, usando la clave de encriptación. |

## Pasos para cambiar una variable de entorno de forma segura

1. Edita el valor en `.env` (nunca en `.env.example`, ese es solo la plantilla de referencia).
2. Ejecuta `php artisan config:clear` (o `php artisan optimize:clear` si no estás seguro de qué caché está afectando).
3. Si el entorno tiene `config:cache` activo (típico en producción), vuelve a cachear: `php artisan config:cache`.
4. Verifica con `php artisan about` o `php artisan config:show <archivo>` que el valor nuevo es el que se está usando realmente.

## ⚠️ Cuidado con `.env` y la configuración

- **Nunca subas el `.env` real a git** (contiene contraseñas, API keys, el `APP_KEY`). Debe estar en `.gitignore`; comparte `.env.example` en su lugar.
- Si `config:cache` está activo en producción y luego cambias el `.env`, la app **no notará el cambio** hasta que ejecutes `config:clear` o vuelvas a cachear.
- `APP_DEBUG=true` jamás en producción: expone trazas de error, rutas y variables internas a cualquier visitante.
- Tras tocar `APP_ENV` o cualquier variable sensible, revisa siempre con `php artisan about` que la app está leyendo lo que esperas.
