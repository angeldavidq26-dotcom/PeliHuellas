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

        Schema::create('adopcion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_solicitud')->unique();
            $table->date('fecha_entrega');
            $table->string('acta_url', 500)->nullable();
            $table->text('observaciones')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adopcion');
    }
};
