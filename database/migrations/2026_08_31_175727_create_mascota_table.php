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
    Schema::create('mascota', function (Blueprint $table) {
        $table->id();

        // --- Columnas de relación ---
        $table->unsignedBigInteger('id_fundacion');
        $table->unsignedBigInteger('id_sede')->nullable();
        $table->unsignedSmallInteger('id_raza')->nullable();

        // --- Datos ---
        $table->string('nombre', 80);
        $table->enum('especie', ['perro', 'gato']);
        $table->enum('sexo', ['macho', 'hembra']);
        $table->enum('tamano', ['pequeno', 'mediano', 'grande', 'gigante']);
        $table->unsignedSmallInteger('edad_aprox_meses')->nullable();
        $table->decimal('peso_kg', 5, 2)->nullable();
        $table->text('descripcion')->nullable();
        $table->boolean('esterilizado')->default(false);
        $table->boolean('vacunado')->default(false);
        $table->date('fecha_ingreso');
        $table->enum('estado', ['disponible', 'espera', 'adoptado'])->default('disponible');
        $table->dateTime('fecha_retiro')->nullable();

        $table->timestamps();

        // --- Llaves foráneas ---
        $table->foreign('id_fundacion')->references('id')->on('fundacion')
              ->onDelete('cascade');
        $table->foreign('id_sede')->references('id_sede')->on('sede_fundacion')
              ->onDelete('set null');
        $table->foreign('id_raza')->references('id_raza')->on('raza')
              ->onDelete('set null');

        // --- Índices ---
        $table->index(['estado', 'especie', 'tamano', 'sexo']);
        $table->index('fecha_retiro');
    });
}
};
