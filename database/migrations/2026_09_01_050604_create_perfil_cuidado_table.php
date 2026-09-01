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

        Schema::create('perfil_cuidado', function (Blueprint $table) {
            $table->id();

            // Relación 1 a 1 con perfil_adoptante (cada adoptante tiene un
            // único perfil de cuidados), de ahí el ->unique().
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->foreign('id_usuario')->references('id_usuario')->on('perfil_adoptante')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('horas_solo_al_dia')->nullable();
            $table->string('lugar_permanente', 200)->nullable();
            $table->string('donde_hace_necesidades', 200)->nullable();
            $table->string('tipo_alimento', 120)->nullable();
            $table->boolean('asume_costo_salud');
            $table->boolean('acepta_tratamiento');
            $table->boolean('acepta_esterilizacion');
            $table->boolean('acepta_cirugias');
            $table->boolean('tiene_otras_mascotas');
            $table->string('cuales_mascotas', 200)->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_cuidado');
    }
};
