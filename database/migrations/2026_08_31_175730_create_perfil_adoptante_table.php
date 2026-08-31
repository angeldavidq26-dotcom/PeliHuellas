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

        Schema::create('perfil_adoptante', function (Blueprint $table) {
            $table->bigInteger('id_perfil_adoptante')->primary();
            $table->unsignedBigInteger('id_usuario')->index();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
            $table->bigInteger('id_vivenda');
            $table->enum('tipo_vivienda', ["casa","apartamento","finca","otro"]);
            $table->boolean('tiene_patio');
            $table->unsignedSmallInteger('metros_aprox')->nullable();
            $table->unsignedTinyInteger('personas_hogar')->nullable();
            $table->boolean('hay_ninos');
            $table->boolean('otras_mascotas');
            $table->boolean('experiencia_previa');
            $table->unsignedTinyInteger('horas_solo_al_dia')->nullable();
            $table->text('motivacion')->nullable();
            $table->boolean('completo');
            $table->dateTime('Fecha_diligenciamiento')->useCurrent();
            $table->bigInteger('cargo');
            $table->bigInteger('nombre_refcia_prsnl');
            $table->bigInteger('parentesco');
            $table->bigInteger('telefono_contacto_refr');
            $table->bigInteger('tienes_mascotas');
            $table->bigInteger('afirmativo_especie');
            $table->bigInteger('desea_adoptar');
            $table->bigInteger('adopcion_familia');
            $table->bigInteger('deacuerdo_adopcion');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_adoptante');
    }
};
