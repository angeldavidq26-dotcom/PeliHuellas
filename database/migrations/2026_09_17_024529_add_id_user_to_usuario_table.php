<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            // Enlaza el registro de dominio (usuario) con la cuenta de
            // autenticación real (users, gestionada por Fortify/Livewire).
            // Nullable porque las usuario creadas para fundaciones (ver
            // MascotaSeeder y SolicitudFundacionController) todavía no
            // tienen una cuenta de acceso propia.
            $table->foreignId('id_user')->nullable()->unique()->after('id_usuario')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_user');
        });
    }
};
