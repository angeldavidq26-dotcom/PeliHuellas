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

        Schema::create('historial_medico', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_mascota')->index();
            $table->foreign('id_mascota')->references('id_mascota')->on('mascota')
                  ->cascadeOnDelete();

            $table->enum('tipo', ["vacuna","desparasitacion","cirugia","consulta","otro"]);
            $table->date('fecha');
            $table->string('veterinario', 160)->nullable();
            $table->string('documento_url', 500)->nullable();
            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('id_usuario_responsable')->index();
            $table->foreign('id_usuario_responsable')->references('id_usuario')->on('usuario');

            $table->dateTime('fecha_registro')->useCurrent();

            $table->index(['id_mascota', 'fecha']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_medico');
    }
};
