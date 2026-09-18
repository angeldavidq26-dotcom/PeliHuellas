<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdoptanteController;
use App\Http\Controllers\FundacionPanelController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\SolicitudFundacionController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureFundacion;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::redirect('/registro', '/register')->name('registro');

Route::view('/', 'index')->name('home');
Route::get('/adoptar', [MascotaController::class, 'index'])->name('mascotas');
Route::get('/adoptar/{mascota}', [MascotaController::class, 'show'])->name('mascotas.show');

Route::prefix('registrar-fundacion')->name('solicitud-fundacion.')->group(function () {
    Route::get('/', [SolicitudFundacionController::class, 'create'])->name('formulario');
    Route::post('/', [SolicitudFundacionController::class, 'store'])->name('enviar');
    Route::get('/gracias', [SolicitudFundacionController::class, 'gracias'])->name('gracias');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/servicios', 'pages.placeholder', ['titulo' => __('Servicios')])->name('servicios');
    Route::get('/solicitudes', [AdoptanteController::class, 'solicitudes'])->name('solicitudes');
    Route::get('/favoritos', [AdoptanteController::class, 'favoritos'])->name('favoritos');
    Route::get('/perfil-adoptante', [AdoptanteController::class, 'perfil'])->name('perfil-adoptante');
    Route::view('/mis-citas', 'pages.placeholder', ['titulo' => __('Mis citas')])->name('citas');
});

Route::prefix('panel-fundacion')->name('fundacion.')->middleware(['auth', EnsureFundacion::class])->group(function () {
    Route::get('/', [FundacionPanelController::class, 'index'])->name('panel');
    Route::get('/mis-animales', [FundacionPanelController::class, 'misAnimales'])->name('mis-animales');
    Route::get('/mis-animales/{mascota}/editar', [FundacionPanelController::class, 'editarAnimal'])->name('mis-animales.editar');
    Route::get('/publicar', [FundacionPanelController::class, 'publicarForm'])->name('publicar');
    Route::get('/sedes', [FundacionPanelController::class, 'sedes'])->name('sedes');
    Route::get('/solicitudes', [FundacionPanelController::class, 'solicitudes'])->name('solicitudes');
    Route::get('/adopciones', [FundacionPanelController::class, 'adopciones'])->name('adopciones');
    Route::get('/adopciones/{adopcion}', [FundacionPanelController::class, 'adopcionShow'])->name('adopciones.show');
    Route::get('/verificacion', [FundacionPanelController::class, 'verificacion'])->name('verificacion');
    Route::get('/configuracion', [FundacionPanelController::class, 'configuracion'])->name('configuracion');
    Route::get('/auditoria', [FundacionPanelController::class, 'auditoria'])->name('auditoria');
});

Route::prefix('panel-admin')->name('admin.')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/', [AdminController::class, 'panel'])->name('panel');
    Route::get('/verificaciones', [AdminController::class, 'verificaciones'])->name('verificaciones');
    Route::get('/solicitudes', [AdminController::class, 'solicitudes'])->name('solicitudes');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::get('/fundaciones', [AdminController::class, 'fundaciones'])->name('fundaciones');
    Route::get('/razas', [AdminController::class, 'razas'])->name('razas');
});

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

require __DIR__.'/settings.php';
