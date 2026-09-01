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

        Schema::create('raza', function (Blueprint $table) {
            $table->smallIncrements('id_raza');
            $table->string('nombre_raza', 80);
            $table->enum('especie', ["perro","gato"]);
            $table->unique(['nombre_raza', 'especie']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raza');
    }
};
