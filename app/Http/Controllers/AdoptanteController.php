<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdoptanteController extends Controller
{
    public function perfil(): View
    {
        return view('pages.perfil-adoptante');
    }

    public function solicitudes(): View
    {
        return view('pages.mis-solicitudes');
    }

    public function favoritos(): View
    {
        return view('pages.mis-favoritos');
    }
}
