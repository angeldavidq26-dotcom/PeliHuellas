<?php

namespace Database\Seeders;

use App\Models\Caracteristica;
use App\Models\Fundacion;
use App\Models\Mascota;
use App\Models\Raza;
use App\Models\SedeFundacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MascotaSeeder extends Seeder
{
    /**
     * Seed fundaciones, sedes, razas, características y mascotas de ejemplo
     * para poder probar el catálogo público de adopción.
     */
    public function run(): void
    {
        $fundaciones = collect([
            ['nombre' => 'Huellas Felices', 'ciudad' => 'Bogotá'],
            ['nombre' => 'Patas Libres', 'ciudad' => 'Cali'],
            ['nombre' => 'AnimalesYA', 'ciudad' => 'Medellín'],
        ])->map(function (array $data) {
            $idUsuario = DB::table('usuario')->insertGetId([
                'tipo_documento' => 'nit',
                'numero_documento' => (string) random_int(100000000, 999999999),
                'nombres' => $data['nombre'],
                'primer_apellido' => 'Fundación',
                'correo' => str($data['nombre'])->slug().'@pelihuellas.test',
                'contrasena_hash' => bcrypt('password'),
                'fecha_registro' => now(),
                'estado' => 'activo',
            ]);

            $fundacion = Fundacion::create([
                'id_usuario' => $idUsuario,
                'nombre' => $data['nombre'],
                'nit' => (string) random_int(100000000, 999999999),
                'correo' => str($data['nombre'])->slug().'@pelihuellas.test',
                'telefono' => '3105550000',
                'estado_verificacion' => 'aprobada',
                'fecha_registro' => now(),
            ]);

            $sede = SedeFundacion::create([
                'id_fundacion' => $fundacion->id_fundacion,
                'nombre' => $data['nombre'].' - '.$data['ciudad'],
                'direccion' => 'Calle 1 # 1-01',
                'ciudad' => $data['ciudad'],
                'telefono' => '3105550000',
                'es_principal' => true,
                'activo' => true,
            ]);

            return ['fundacion' => $fundacion, 'sede' => $sede];
        });

        $razas = collect([
            'Pitbull Americano' => 'perro',
            'Labrador Retriever' => 'perro',
            'Mestiza' => 'perro',
            'Beagle' => 'perro',
            'Persa Himalayo' => 'gato',
            'Doméstico' => 'gato',
            'Siamés' => 'gato',
            'Francés' => 'perro',
        ])->map(fn (string $especie, string $nombre) => Raza::create([
            'nombre_raza' => $nombre,
            'especie' => $especie,
        ]));

        $caracteristicas = collect(['Juguetón', 'Tranquilo', 'Sociable con niños', 'Apto apartamento', 'Energético', 'Sociable'])
            ->mapWithKeys(fn (string $nombre) => [$nombre => Caracteristica::create(['nombre' => $nombre])]);

        $huellasFelices = $fundaciones[0];
        $patasLibres = $fundaciones[1];
        $animalesYa = $fundaciones[2];

        $mascotas = [
            ['nombre' => 'Zeus', 'especie' => 'perro', 'sexo' => 'macho', 'tamano' => 'grande', 'edad_aprox_meses' => 24, 'raza' => 'Pitbull Americano', 'sede' => $huellasFelices, 'esterilizado' => true, 'tags' => ['Juguetón', 'Energético', 'Sociable con niños'], 'foto' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=500&q=80'],
            ['nombre' => 'Cleo', 'especie' => 'gato', 'sexo' => 'hembra', 'tamano' => 'mediano', 'edad_aprox_meses' => 36, 'raza' => 'Persa Himalayo', 'sede' => $animalesYa, 'esterilizado' => false, 'tags' => ['Tranquilo', 'Apto apartamento', 'Sociable con niños'], 'foto' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=500&q=80'],
            ['nombre' => 'Bruno', 'especie' => 'perro', 'sexo' => 'macho', 'tamano' => 'grande', 'edad_aprox_meses' => 48, 'raza' => 'Labrador Retriever', 'sede' => $huellasFelices, 'esterilizado' => true, 'tags' => ['Juguetón', 'Sociable con niños', 'Tranquilo'], 'foto' => 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&w=500&q=80'],
            ['nombre' => 'Mia', 'especie' => 'gato', 'sexo' => 'hembra', 'tamano' => 'pequeno', 'edad_aprox_meses' => 12, 'raza' => 'Doméstico', 'sede' => $animalesYa, 'esterilizado' => false, 'tags' => ['Tranquilo', 'Apto apartamento'], 'foto' => 'https://images.unsplash.com/photo-1495360010541-f48722b34f7d?auto=format&fit=crop&w=500&q=80'],
            ['nombre' => 'Nala', 'especie' => 'perro', 'sexo' => 'hembra', 'tamano' => 'mediano', 'edad_aprox_meses' => 24, 'raza' => 'Francés', 'sede' => $animalesYa, 'esterilizado' => true, 'tags' => ['Sociable con niños', 'Juguetón', 'Energético'], 'foto' => 'https://images.unsplash.com/photo-1583512603806-077998240c7a?auto=format&fit=crop&w=500&q=80'],
            ['nombre' => 'Simba', 'especie' => 'gato', 'sexo' => 'macho', 'tamano' => 'mediano', 'edad_aprox_meses' => 60, 'raza' => 'Siamés', 'sede' => $patasLibres, 'esterilizado' => true, 'tags' => ['Tranquilo', 'Apto apartamento'], 'foto' => 'https://images.unsplash.com/photo-1533738363-b7f9aef128ce?auto=format&fit=crop&w=500&q=80'],
        ];

        foreach ($mascotas as $data) {
            $mascota = Mascota::create([
                'id_fundacion' => $data['sede']['fundacion']->id_fundacion,
                'id_sede' => $data['sede']['sede']->id_sede,
                'id_raza' => $razas[$data['raza']]->id_raza,
                'nombre' => $data['nombre'],
                'especie' => $data['especie'],
                'sexo' => $data['sexo'],
                'tamano' => $data['tamano'],
                'edad_aprox_meses' => $data['edad_aprox_meses'],
                'esterilizado' => $data['esterilizado'],
                'vacunado' => true,
                'fecha_ingreso' => now()->subDays(random_int(1, 60)),
                'estado' => 'disponible',
            ]);

            $mascota->fotos()->create([
                'url' => $data['foto'],
                'orden' => 1,
                'es_principal' => true,
            ]);

            $mascota->caracteristicas()->attach(
                collect($data['tags'])->map(fn (string $tag) => $caracteristicas[$tag]->id_caracteristica)
            );
        }
    }
}
