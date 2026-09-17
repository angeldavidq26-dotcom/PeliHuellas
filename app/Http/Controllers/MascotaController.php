<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Mascota;
use App\Models\SedeFundacion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MascotaController extends Controller
{
    public function index(Request $request): View
    {
        $especie = $request->string('especie')->lower()->toString();
        $sexo = $request->string('sexo')->lower()->toString();
        $ciudad = $request->string('ciudad')->toString();
        $tamanos = array_values(array_intersect(
            (array) $request->input('tamano', []),
            Mascota::TAMANOS
        ));
        $caracteristicasIds = array_map('intval', (array) $request->input('caracteristicas', []));
        $orden = $request->string('orden', 'recientes')->toString();

        $mascotas = Mascota::query()
            ->disponibles()
            ->with(['fundacion', 'sede', 'raza', 'fotoPrincipal', 'caracteristicas'])
            ->when(in_array($especie, ['perro', 'gato'], true), fn ($query) => $query->where('especie', $especie))
            ->when(in_array($sexo, ['macho', 'hembra'], true), fn ($query) => $query->where('sexo', $sexo))
            ->when($tamanos !== [], fn ($query) => $query->whereIn('tamano', $tamanos))
            ->when($ciudad !== '', fn ($query) => $query->whereHas(
                'sede',
                fn ($sedeQuery) => $sedeQuery->where('ciudad', $ciudad)
            ))
            ->when($caracteristicasIds !== [], function ($query) use ($caracteristicasIds) {
                foreach ($caracteristicasIds as $id) {
                    $query->whereHas(
                        'caracteristicas',
                        fn ($caracteristicaQuery) => $caracteristicaQuery->where('caracteristica.id_caracteristica', $id)
                    );
                }
            })
            ->when($orden === 'nombre', fn ($query) => $query->orderBy('nombre'))
            ->when($orden === 'edad_asc', fn ($query) => $query->orderBy('edad_aprox_meses'))
            ->when($orden === 'edad_desc', fn ($query) => $query->orderByDesc('edad_aprox_meses'))
            ->when(! in_array($orden, ['nombre', 'edad_asc', 'edad_desc'], true), fn ($query) => $query->orderByDesc('fecha_ingreso'))
            ->paginate(12)
            ->withQueryString();

        $ciudades = SedeFundacion::query()
            ->where('activo', true)
            ->whereNotNull('ciudad')
            ->distinct()
            ->orderBy('ciudad')
            ->pluck('ciudad');

        $caracteristicas = Caracteristica::query()->orderBy('nombre')->get();

        return view('mascotas', [
            'mascotas' => $mascotas,
            'ciudades' => $ciudades,
            'caracteristicas' => $caracteristicas,
            'filtros' => [
                'especie' => $especie,
                'sexo' => $sexo,
                'ciudad' => $ciudad,
                'tamano' => $tamanos,
                'caracteristicas' => $caracteristicasIds,
                'orden' => $orden,
            ],
        ]);
    }

    public function show(Mascota $mascota): View
    {
        $mascota->load(['fundacion', 'sede', 'raza', 'fotos', 'caracteristicas', 'historialMedico']);

        return view('mascota-detalle', [
            'mascota' => $mascota,
        ]);
    }
}
