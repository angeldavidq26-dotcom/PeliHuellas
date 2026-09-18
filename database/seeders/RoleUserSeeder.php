<?php

namespace Database\Seeders;

use App\Actions\Teams\CreateTeam;
use App\Models\Fundacion;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Crea cuentas demo para cada rol y las deja listas para iniciar sesión:
     * con su "team" personal (necesario para el flujo de login normal) y,
     * cuando aplica, su registro vinculado en la tabla de dominio `usuario`
     * para que las vistas de adoptante/fundación muestren datos reales.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@pelihuellas.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'role' => 'administracion',
                'email_verified_at' => now(),
            ]
        );

        $fundacionUser = User::updateOrCreate(
            ['email' => 'fundacion@pelihuellas.com'],
            [
                'name' => 'Fundación Demo',
                'password' => bcrypt('password'),
                'role' => 'fundacion',
                'email_verified_at' => now(),
            ]
        );

        $adoptanteUser = User::updateOrCreate(
            ['email' => 'usuario@pelihuellas.com'],
            [
                'name' => 'Usuario Demo',
                'password' => bcrypt('password'),
                'role' => 'usuario',
                'email_verified_at' => now(),
            ]
        );

        $this->ensurePersonalTeam($admin);
        $this->ensurePersonalTeam($fundacionUser);
        $this->ensurePersonalTeam($adoptanteUser);

        $this->linkFundacionDemo($fundacionUser);
        $this->linkAdoptanteDemo($adoptanteUser);
    }

    private function ensurePersonalTeam(User $user): void
    {
        if ($user->personalTeam()) {
            return;
        }

        app(CreateTeam::class)->handle($user, $user->name."'s Team", isPersonal: true);
    }

    /**
     * Vincula la cuenta demo de fundación con la primera fundación sembrada
     * (la misma que el panel de fundación muestra por defecto), para que al
     * iniciar sesión con ella se vea información consistente.
     */
    private function linkFundacionDemo(User $user): void
    {
        $fundacion = Fundacion::query()->orderBy('id_fundacion')->first();

        if (! $fundacion) {
            return;
        }

        Usuario::query()
            ->where('id_usuario', $fundacion->id_usuario)
            ->update(['id_user' => $user->id]);
    }

    private function linkAdoptanteDemo(User $user): void
    {
        $usuario = Usuario::firstOrNew(['correo' => 'usuario@pelihuellas.com']);

        $usuario->fill([
            'tipo_documento' => 'cc',
            'numero_documento' => '1000000001',
            'nombres' => 'Usuario',
            'primer_apellido' => 'Demo',
            'contrasena_hash' => bcrypt('password'),
            'fecha_registro' => $usuario->fecha_registro ?? now(),
            'estado' => 'activo',
            'id_user' => $user->id,
        ]);

        $usuario->save();
    }
}
