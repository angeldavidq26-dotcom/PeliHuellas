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

        Schema::create('seguimiento_adopcion', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_adopcion')->index();
            $table->foreign('id_adopcion')->references('id')->on('adopcion')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('id_usuario_responsable')->index();
            $table->foreign('id_usuario_responsable')->references('id_usuario')->on('usuario');

            $table->dateTime('fecha_seguimiento')->useCurrent();
            $table->enum('estado_animal', ["excelente","bueno","regular","preocupante"]);
            $table->text('observaciones')->nullable();
            $table->json('fotos')->nullable();

            $table->index(['id_adopcion', 'fecha_seguimiento']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_adopcion');
    }
};
