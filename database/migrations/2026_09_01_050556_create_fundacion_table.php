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

        Schema::create('fundacion', function (Blueprint $table) {
            // Nombrada 'id_fundacion' porque sede_fundacion y mascota
            // referencian esta clave por ese nombre.
            $table->id('id_fundacion');
            $table->unsignedBigInteger('id_usuario')->index();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
            $table->string('nombre', 160);
            $table->string('nit', 30)->unique();
            $table->string('correo', 160);
            $table->string('telefono', 25);
            $table->text('descripcion')->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->unsignedSmallInteger('capacidad')->nullable();
            $table->enum('estado_verificacion', ["pendiente","aprobada","rechazada"]);
            $table->dateTime('fecha_registro')->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fundacion');
    }
};
