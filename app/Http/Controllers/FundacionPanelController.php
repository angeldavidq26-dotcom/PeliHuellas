<?php

namespace App\Http\Controllers;

use App\Models\Adopcion;
use App\Models\Fundacion;
use App\Models\Mascota;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FundacionPanelController extends Controller
{
    public function index(): View
    {
        $fundacion = $this->fundacion();

        $stats = [
            'disponibles' => $fundacion->mascotas()->where('estado', 'disponible')->count(),
            'en_espera' => $fundacion->mascotas()->where('estado', 'espera')->count(),
            'solicitudes_sin_revisar' => DB::table('solicitud_adopcion')
                ->join('mascota', 'mascota.id_mascota', '=', 'solicitud_adopcion.id_mascota')
                ->where('mascota.id_fundacion', $fundacion->id_fundacion)
                ->where('solicitud_adopcion.estado', 'pendiente')
                ->count(),
            'adopciones_del_mes' => $fundacion->mascotas()
                ->where('estado', 'adoptado')
                ->whereMonth('fecha_retiro', now()->month)
                ->whereYear('fecha_retiro', now()->year)
                ->count(),
        ];

        $ultimasSolicitudes = DB::table('solicitud_adopcion')
            ->join('mascota', 'mascota.id_mascota', '=', 'solicitud_adopcion.id_mascota')
            ->join('usuario', 'usuario.id_usuario', '=', 'solicitud_adopcion.id_usuario')
            ->where('mascota.id_fundacion', $fundacion->id_fundacion)
            ->orderByDesc('solicitud_adopcion.fecha_solicitud')
            ->limit(5)
            ->get([
                'mascota.nombre as animal',
                'usuario.nombres as solicitante_nombres',
                'usuario.primer_apellido as solicitante_apellido',
                'solicitud_adopcion.fecha_solicitud',
                'solicitud_adopcion.estado',
            ]);

        $misAnimales = $fundacion->mascotas()
            ->with(['fotoPrincipal', 'raza'])
            ->latest('fecha_ingreso')
            ->take(4)
            ->get();

        return view('pages.fundacion.panel', [
            'fundacion' => $fundacion,
            'stats' => $stats,
            'ultimasSolicitudes' => $ultimasSolicitudes,
            'misAnimales' => $misAnimales,
        ]);
    }

    public function misAnimales(): View
    {
        return view('pages.fundacion.mis-animales', [
            'fundacion' => $this->fundacion(),
        ]);
    }

    public function sedes(): View
    {
        $fundacion = $this->fundacion();

        return view('pages.fundacion.sedes', [
            'fundacion' => $fundacion,
            'sedes' => $fundacion->sedes,
        ]);
    }

    public function publicarForm(): View
    {
        return view('pages.fundacion.publicar', [
            'fundacion' => $this->fundacion(),
        ]);
    }

    public function editarAnimal(Mascota $mascota): View
    {
        $fundacion = $this->fundacion();

        abort_unless($mascota->id_fundacion === $fundacion->id_fundacion, 404);

        return view('pages.fundacion.editar-animal', [
            'fundacion' => $fundacion,
            'mascota' => $mascota,
        ]);
    }

    public function adopciones(): View
    {
        return view('pages.fundacion.adopciones', [
            'fundacion' => $this->fundacion(),
        ]);
    }

    public function adopcionShow(Adopcion $adopcion): View
    {
        $fundacion = $this->fundacion();

        abort_unless($adopcion->solicitud->mascota->id_fundacion === $fundacion->id_fundacion, 404);

        return view('pages.fundacion.adopcion-detalle', [
            'fundacion' => $fundacion,
            'adopcion' => $adopcion,
        ]);
    }

    public function verificacion(): View
    {
        return view('pages.fundacion.verificacion', [
            'fundacion' => $this->fundacion(),
        ]);
    }

    public function configuracion(): View
    {
        return view('pages.fundacion.configuracion', [
            'fundacion' => $this->fundacion(),
        ]);
    }

    public function solicitudes(): View
    {
        return view('pages.fundacion.solicitudes', [
            'fundacion' => $this->fundacion(),
        ]);
    }

    public function auditoria(): View
    {
        return view('pages.fundacion.auditoria', [
            'fundacion' => $this->fundacion(),
        ]);
    }

    public function placeholder(string $titulo): View
    {
        return view('pages.fundacion.placeholder', [
            'fundacion' => $this->fundacion(),
            'titulo' => $titulo,
            'active' => str(request()->route()->getName())->after('fundacion.')->toString(),
        ]);
    }

    private function fundacion(): Fundacion
    {
        return Fundacion::query()->with('sedes')->orderBy('id_fundacion')->firstOrFail();
    }
}
