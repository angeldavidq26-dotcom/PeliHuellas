<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pelihuellas.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'role' => 'administracion',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'fundacion@pelihuellas.com'],
            [
                'name' => 'Fundación Demo',
                'password' => bcrypt('password'),
                'role' => 'fundacion',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'usuario@pelihuellas.com'],
            [
                'name' => 'Usuario Demo',
                'password' => bcrypt('password'),
                'role' => 'usuario',
                'email_verified_at' => now(),
            ]
        );
    }
}
