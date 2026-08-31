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

        Schema::create('cuidados_salud', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_perfil_adopcion')->index();
            $table->foreign('id_perfil_adopcion')->references('id_vivenda')->on('perfil_adoptante');
            $table->bigInteger('tiempo_sola_mscta');
            $table->bigInteger('lugar_permanente');
            $table->bigInteger('realizara_ncesidades');
            $table->bigInteger('tipo_alimento');
            $table->bigInteger('costo_salud');
            $table->bigInteger('disposicion_tratamiento');
            $table->bigInteger('esterilzacion');
            $table->bigInteger('cirugias');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuidados_salud');
    }
};
