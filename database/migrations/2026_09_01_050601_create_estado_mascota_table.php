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

        Schema::create('estado_mascota', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mascota');
            $table->foreign('id_mascota')->references('id_mascota')->on('mascota');
            $table->enum('estado_anterior', ["disponible","espera","adoptado"]);
            $table->enum('estado', ["disponible","espera","adoptado"]);
            $table->string('motivo', 300)->nullable();
            $table->dateTime('fecha_cambio')->useCurrent();
            $table->unsignedBigInteger('id_usuario_responsable')->index();
            $table->foreign('id_usuario_responsable')->references('id_usuario')->on('usuario');
            $table->index(['id_mascota', 'fecha_cambio']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_mascota');
    }
};
