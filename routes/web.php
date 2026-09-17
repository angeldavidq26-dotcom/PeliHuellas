<?php

use App\Http\Controllers\AdminFundacionController;
use App\Http\Controllers\AdoptanteController;
use App\Http\Controllers\FundacionPanelController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\SolicitudFundacionController;
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
    Route::get('/perfil-adoptante', [AdoptanteController::class, 'perfil'])->name('perfil-adoptante');
    Route::view('/mis-citas', 'pages.placeholder', ['titulo' => __('Mis citas')])->name('citas');
});

Route::prefix('panel-fundacion')->name('fundacion.')->group(function () {
    Route::get('/', [FundacionPanelController::class, 'index'])->name('panel');
    Route::get('/mis-animales', [FundacionPanelController::class, 'misAnimales'])->name('mis-animales');
    Route::get('/mis-animales/{mascota}/editar', [FundacionPanelController::class, 'editarAnimal'])->name('mis-animales.editar');
    Route::get('/publicar', [FundacionPanelController::class, 'publicarForm'])->name('publicar');
    Route::get('/sedes', [FundacionPanelController::class, 'sedes'])->name('sedes');
    Route::get('/solicitudes', [FundacionPanelController::class, 'solicitudes'])->name('solicitudes');
    Route::get('/verificacion', [FundacionPanelController::class, 'verificacion'])->name('verificacion');
    Route::get('/configuracion', [FundacionPanelController::class, 'configuracion'])->name('configuracion');
});

Route::prefix('panel-admin')->name('admin.')->group(function () {
    Route::get('/fundaciones', [AdminFundacionController::class, 'index'])->name('fundaciones');
});

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

require __DIR__.'/settings.php';
