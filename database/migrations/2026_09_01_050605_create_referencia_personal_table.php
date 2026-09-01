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

        Schema::create('referencia_personal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario')->index();
            $table->foreign('id_usuario')->references('id_usuario')->on('perfil_adoptante')
                  ->cascadeOnDelete();
            $table->string('nombre', 160);
            $table->string('parentesco', 80);
            $table->string('telefono', 25);
            $table->string('ocupacion', 120)->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencia_personal');
    }
};
