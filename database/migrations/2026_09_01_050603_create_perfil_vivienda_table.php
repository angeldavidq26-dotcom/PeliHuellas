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

        Schema::create('perfil_vivienda', function (Blueprint $table) {
            $table->id();

            // Relación 1 a 1 con perfil_adoptante (cada adoptante tiene un
            // único perfil de vivienda), de ahí el ->unique().
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->foreign('id_usuario')->references('id_usuario')->on('perfil_adoptante')
                  ->cascadeOnDelete();

            $table->enum('tipo_inmueble', ["casa","apartamento","finca","otro"]);
            $table->enum('tenencia', ["propia","arrendada","familiar"]);
            $table->unsignedSmallInteger('area_m2')->nullable();
            $table->boolean('tiene_patio');
            $table->boolean('area_cubierta');
            $table->enum('lugar_mascota', ["interior","patio","ambos"]);
            $table->unsignedTinyInteger('personas_hogar')->nullable();
            $table->string('con_quien_vive', 200)->nullable();
            $table->boolean('hay_ninos');
            $table->boolean('persona_con_alergia')->comment('Alergia o enfermedad que el animal pueda afectar');
            $table->string('detalle_alergia', 300)->nullable();
            $table->boolean('todos_aceptan');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_vivienda');
    }
};
