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
        Schema::disableForeignKeyConstraints();

        Schema::create('solicitud_adopcion', function (Blueprint $table) {
            // Nombrada 'id_solicitud' porque adopcion referencia esta
            // clave por ese nombre (una solicitud aprobada genera una
            // adopción, nunca al revés).
            $table->id('id_solicitud');

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');

            $table->unsignedBigInteger('id_mascota');
            $table->foreign('id_mascota')->references('id_mascota')->on('mascota');

            $table->dateTime('fecha_solicitud')->useCurrent();
            $table->enum('estado', ["pendiente","aprobada","en_pausa","rechazada","completada","no_concretada","cancelada"]);
            $table->text('mensaje_solicitante')->nullable();
            $table->text('observaciones_fundacion')->nullable();
            $table->dateTime('fecha_resolucion')->nullable();
            $table->index(['id_mascota', 'estado']);
            $table->index(['id_usuario', 'estado']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_adopcion');
    }
};
