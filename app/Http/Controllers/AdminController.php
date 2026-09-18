<?php

namespace App\Http\Controllers;

use App\Models\Adopcion;
use App\Models\Fundacion;
use App\Models\Mascota;
use App\Models\SolicitudAdopcion;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function panel(): View
    {
        $stats = [
            'fundaciones_pendientes' => Fundacion::query()->where('estado_verificacion', 'pendiente')->count(),
            'fundaciones_activas' => Fundacion::query()->where('estado_verificacion', 'aprobada')->count(),
            'animales_publicados' => Mascota::query()->count(),
            'solicitudes_sin_resolver' => SolicitudAdopcion::query()->where('estado', 'pendiente')->count(),
            'adopciones_del_mes' => Adopcion::query()
                ->whereMonth('fecha_entrega', now()->month)
                ->whereYear('fecha_entrega', now()->year)
                ->count(),
        ];

        $solicitudesUrgentes = SolicitudAdopcion::query()
            ->where('estado', 'pendiente')
            ->where('fecha_solicitud', '<=', now()->subDays(5))
            ->count();

        $verificacionesAntiguas = Fundacion::query()
            ->where('estado_verificacion', 'pendiente')
            ->with('sedes')
            ->orderBy('fecha_registro')
            ->take(3)
            ->get();

        return view('pages.admin.panel', [
            'stats' => $stats,
            'solicitudesUrgentes' => $solicitudesUrgentes,
            'verificacionesAntiguas' => $verificacionesAntiguas,
        ]);
    }

    public function verificaciones(): View
    {
        return view('pages.admin.verificaciones');
    }

    public function solicitudes(): View
    {
        return view('pages.admin.solicitudes');
    }

    public function usuarios(): View
    {
        return view('pages.admin.usuarios');
    }

    public function fundaciones(): View
    {
        $fundaciones = Fundacion::query()
            ->withCount(['mascotas', 'sedes'])
            ->with(['sedes' => fn ($q) => $q->where('es_principal', true)])
            ->orderBy('nombre')
            ->get();

        return view('pages.admin.fundaciones', [
            'fundaciones' => $fundaciones,
        ]);
    }

    public function razas(): View
    {
        return view('pages.admin.razas');
    }
}
