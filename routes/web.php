<?php

use App\Http\Controllers\FundacionPanelController;
use App\Http\Controllers\MascotaController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::redirect('/registro', '/register')->name('registro');

Route::view('/', 'index')->name('home');
Route::get('/adoptar', [MascotaController::class, 'index'])->name('mascotas');

Route::middleware(['auth'])->group(function () {
    Route::view('/servicios', 'pages.placeholder', ['titulo' => __('Servicios')])->name('servicios');
    Route::view('/solicitudes', 'pages.placeholder', ['titulo' => __('Solicitudes')])->name('solicitudes');
    Route::view('/mis-citas', 'pages.placeholder', ['titulo' => __('Mis citas')])->name('citas');
});

Route::prefix('panel-fundacion')->name('fundacion.')->group(function () {
    Route::get('/', [FundacionPanelController::class, 'index'])->name('panel');
    Route::get('/mis-animales', [FundacionPanelController::class, 'misAnimales'])->name('mis-animales');
    Route::get('/publicar', [FundacionPanelController::class, 'publicarForm'])->name('publicar');
    Route::get('/sedes', [FundacionPanelController::class, 'sedes'])->name('sedes');
    Route::get('/solicitudes', [FundacionPanelController::class, 'placeholder'])->name('solicitudes')->defaults('titulo', 'Solicitudes');
    Route::get('/verificacion', [FundacionPanelController::class, 'placeholder'])->name('verificacion')->defaults('titulo', 'Verificación');
    Route::get('/configuracion', [FundacionPanelController::class, 'placeholder'])->name('configuracion')->defaults('titulo', 'Configuración');
});

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

require __DIR__.'/settings.php';
