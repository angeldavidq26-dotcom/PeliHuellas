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
        Schema::table('solicitud_adopcion', function (Blueprint $table) {
            // Quién resolvió la solicitud (la aprobó o la rechazó): puede ser
            // un usuario de la fundación o, en una intervención administrativa,
            // un administrador. Nula mientras la solicitud siga pendiente o en pausa.
            $table->unsignedBigInteger('id_usuario_responsable')->nullable()->after('fecha_resolucion');
            $table->foreign('id_usuario_responsable')->references('id_usuario')->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud_adopcion', function (Blueprint $table) {
            $table->dropForeign(['id_usuario_responsable']);
            $table->dropColumn('id_usuario_responsable');
        });
    }
};
