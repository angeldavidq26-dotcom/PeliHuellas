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

        Schema::create('foto_mascota', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mascota')->index();
            $table->foreign('id_mascota')->references('id_mascota')->on('mascota')
                  ->cascadeOnDelete();
            $table->string('url', 500);
            $table->unsignedTinyInteger('orden');
            $table->boolean('es_principal');
            $table->index(['id_mascota', 'orden']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_mascota');
    }
};
