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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_documento', ["cc","ce","ti","pasaporte","ni"]);
            $table->string('numero_documento', 30);
            $table->string('nombres', 80);
            $table->string('primer_apellido', 80);
            $table->bigInteger('edad');
            $table->string('segundo_apellido', 80)->nullable();
            $table->string('telefono', 25)->nullable();
            $table->string('correo', 160)->unique();
            $table->string('direccion', 200)->nullable();
            $table->string('contrasena_hash', 255);
            $table->bigInteger('fecha_registro');
            $table->enum('estado', ["activo","inactivo","suspendido"]);
            $table->bigInteger('descripcion');
            $table->bigInteger('foto');
            $table->bigInteger('estado_verificacion');
            $table->bigInteger('red_social');
            $table->bigInteger('new_column');
            $table->unique(['tipo_documento', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
