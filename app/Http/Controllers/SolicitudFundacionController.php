<?php

namespace App\Http\Controllers;

use App\Models\Fundacion;
use App\Models\SedeFundacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SolicitudFundacionController extends Controller
{
    public function create(): View
    {
        return view('pages.solicitud-fundacion.formulario');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:160'],
            'nit' => ['required', 'string', 'max:30', Rule::unique('fundacion', 'nit')],
            'correo' => ['required', 'email', 'max:160', Rule::unique('usuario', 'correo')],
            'telefono' => ['required', 'string', 'max:25'],
            'ciudad' => ['required', 'string', 'max:80'],
            'direccion' => ['required', 'string', 'max:200'],
            'capacidad' => ['nullable', 'integer', 'min:1', 'max:2000'],
            'descripcion' => ['required', 'string', 'min:20', 'max:2000'],
            'contacto_nombre' => ['required', 'string', 'max:160'],
        ]);

        DB::transaction(function () use ($datos): void {
            $idUsuario = DB::table('usuario')->insertGetId([
                'tipo_documento' => 'nit',
                'numero_documento' => $datos['nit'],
                'nombres' => $datos['contacto_nombre'],
                'primer_apellido' => __('Fundación'),
                'correo' => $datos['correo'],
                'telefono' => $datos['telefono'],
                'contrasena_hash' => bcrypt(str()->random(32)),
                'fecha_registro' => now(),
                'estado' => 'activo',
            ]);

            $fundacion = Fundacion::create([
                'id_usuario' => $idUsuario,
                'nombre' => $datos['nombre'],
                'nit' => $datos['nit'],
                'correo' => $datos['correo'],
                'telefono' => $datos['telefono'],
                'descripcion' => $datos['descripcion'],
                'capacidad' => $datos['capacidad'] ?? null,
                'estado_verificacion' => 'pendiente',
                'fecha_registro' => now(),
            ]);

            SedeFundacion::create([
                'id_fundacion' => $fundacion->id_fundacion,
                'nombre' => __('Sede principal'),
                'direccion' => $datos['direccion'],
                'ciudad' => $datos['ciudad'],
                'telefono' => $datos['telefono'],
                'es_principal' => true,
                'activo' => true,
            ]);
        });

        return redirect()->route('solicitud-fundacion.gracias');
    }

    public function gracias(): View
    {
        return view('pages.solicitud-fundacion.gracias');
    }
}
