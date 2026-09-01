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

        Schema::create('sede_fundacion', function (Blueprint $table) {
            // Nombrada 'id_sede' porque mascota referencia esta clave por ese nombre.
            $table->id('id_sede');
            $table->unsignedBigInteger('id_fundacion')->index();
            $table->foreign('id_fundacion')->references('id_fundacion')->on('fundacion');
            $table->string('nombre', 120);
            $table->string('direccion', 200);
            $table->string('ciudad', 80)->index();
            $table->string('telefono', 25)->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->boolean('es_principal');
            $table->boolean('activo')->default(true);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sede_fundacion');
    }
};
