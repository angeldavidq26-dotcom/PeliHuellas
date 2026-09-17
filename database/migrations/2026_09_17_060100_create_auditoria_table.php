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

        Schema::create('auditoria', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_usuario')->nullable()->index();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->nullOnDelete();

            $table->string('accion', 80);

            // Polimórfico a propósito: a diferencia del resto del esquema
            // (donde cada tabla referencia un único padre por FK explícita),
            // una bitácora de auditoría necesita apuntar a cualquier entidad
            // administrable (mascota, solicitud, fundación, etc.) por diseño.
            $table->nullableMorphs('auditable');

            $table->json('datos')->nullable();
            $table->string('ip', 45)->nullable();
            $table->dateTime('fecha')->useCurrent();

            $table->index(['id_usuario', 'fecha']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};
