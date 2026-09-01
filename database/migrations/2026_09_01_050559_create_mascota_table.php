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

        Schema::create('mascota', function (Blueprint $table) {
            // Nombrada 'id_mascota' porque foto_mascota, estado_mascota y
            // solicitud_adopcion referencian esta clave por ese nombre.
            $table->id('id_mascota');
            $table->unsignedBigInteger('id_fundacion')->index();
            $table->foreign('id_fundacion')->references('id_fundacion')->on('fundacion');
            $table->unsignedBigInteger('id_sede')->index()->nullable();
            $table->foreign('id_sede')->references('id_sede')->on('sede_fundacion');
            $table->unsignedSmallInteger('id_raza')->index()->nullable();
            $table->foreign('id_raza')->references('id_raza')->on('raza');
            $table->string('nombre', 80);
            $table->enum('especie', ["perro","gato"]);
            $table->enum('sexo', ["macho","hembra"]);
            $table->enum('tamano', ["pequeno","mediano","grande","gigante"]);
            $table->unsignedSmallInteger('edad_aprox_meses')->nullable();
            $table->decimal('peso_kg', 5, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('esterilizado');
            $table->boolean('vacunado');
            $table->date('fecha_ingreso');
            $table->enum('estado', ["disponible","espera","adoptado"]);
            $table->dateTime('fecha_retiro')->nullable();
            $table->index(['estado', 'fecha_retiro', 'especie', 'tamano', 'sexo']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascota');
    }
};
