<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminFundacionController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.fundaciones');
    }
}
