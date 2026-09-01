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

        Schema::create('usuario_rol', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('id_rol');
            $table->foreign('id_rol')->references('id_rol')->on('rol')
                  ->cascadeOnDelete();

            $table->dateTime('fecha_asignacion')->useCurrent();

            // Evita asignar el mismo rol dos veces al mismo usuario.
            $table->unique(['id_usuario', 'id_rol']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_rol');
    }
};
