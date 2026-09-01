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
            // perfil_adoptante extiende a usuario en una relación 1 a 1:
            // su propia clave primaria ES la clave foránea hacia usuario
            // (no lleva un 'id' autoincremental aparte).
            $table->unsignedBigInteger('id_usuario')->primary();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')
                  ->cascadeOnDelete();

            $table->string('ocupacion', 120)->nullable();
            $table->enum('desea_adoptar', ["perro","gato","ambos","indiferente"]);
            $table->boolean('experiencia_previa');
            $table->boolean('decision_familiar')->comment('La decision la tomo toda la familia');
            $table->boolean('todos_de_acuerdo');
            $table->text('motivacion')->nullable();
            $table->boolean('completo');
            $table->dateTime('fecha_diligenciamiento')->useCurrent();
            $table->dateTime('fecha_actualizacion')->useCurrent();
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
