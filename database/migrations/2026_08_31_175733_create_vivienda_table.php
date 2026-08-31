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

        Schema::create('vivienda', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_perfil_adoptante')->index();
            $table->foreign('id_perfil_adoptante')->references('id_vivenda')->on('perfil_adoptante');
            $table->bigInteger('vives_personas');
            $table->bigInteger('con_vives');
            $table->bigInteger('peersona_enfermedad');
            $table->bigInteger('tipo_inmueble');
            $table->bigInteger('area_vivienda');
            $table->bigInteger('aceptacion_mascota');
            $table->bigInteger('lugar_mascota');
            $table->bigInteger('area_cubierta');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vivienda');
    }
};
