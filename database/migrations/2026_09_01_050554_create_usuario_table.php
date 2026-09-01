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
            // Nombrada 'id_usuario' (en vez del genérico 'id') porque el resto
            // de tablas del esquema referencian esta clave por ese nombre.
            $table->id('id_usuario');
            $table->enum('tipo_documento', ["cc","ce","ti","pasaporte","nit"]);
            $table->string('numero_documento', 30);
            $table->string('nombres', 80);
            $table->string('primer_apellido', 80);
            $table->string('segundo_apellido', 80)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 25)->nullable();
            $table->string('correo', 160)->unique();
            $table->string('direccion', 200)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('foto_url', 500)->nullable();
            $table->string('red_social', 200)->nullable();
            $table->string('contrasena_hash', 255);
            $table->dateTime('fecha_registro')->useCurrent();
            $table->enum('estado', ["activo","inactivo","suspendido"]);
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
