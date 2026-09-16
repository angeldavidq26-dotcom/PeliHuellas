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

        Schema::create('mascota_caracteristica', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_mascota');
            $table->foreign('id_mascota')->references('id_mascota')->on('mascota')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('id_caracteristica');
            $table->foreign('id_caracteristica')->references('id_caracteristica')->on('caracteristica')
                  ->cascadeOnDelete();

            $table->unique(['id_mascota', 'id_caracteristica']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascota_caracteristica');
    }
};
