<?php

use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

// Route::view('/mascotas','mascota')->name('mascota')



Route::view('/', 'welcome')->name('home');
Route::view('/panel-fundacion', 'Panel_fundacion')->name('panel.fundacion');


Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

require __DIR__.'/settings.php';
