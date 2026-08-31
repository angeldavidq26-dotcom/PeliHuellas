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
            $table->bigInteger('id_usuario_rol')->primary();
            $table->unsignedBigInteger('id_usuario')->index();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
            $table->unsignedTinyInteger('id_rol')->index();
            $table->foreign('id_rol')->references('id_rol')->on('rol');
            $table->dateTime('fecha_asignacion')->useCurrent();
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
