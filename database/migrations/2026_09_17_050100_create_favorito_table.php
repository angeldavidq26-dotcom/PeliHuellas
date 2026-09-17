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

        Schema::create('favorito', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_usuario')->index();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('id_mascota')->index();
            $table->foreign('id_mascota')->references('id_mascota')->on('mascota')
                  ->cascadeOnDelete();

            $table->dateTime('fecha_guardado')->useCurrent();

            $table->unique(['id_usuario', 'id_mascota']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorito');
    }
};
