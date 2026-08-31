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
    Schema::create('solicitud_adopcion', function (Blueprint $table) {
        $table->id();

        // Columnas de relación (usa foreignId + constrained: 1 línea en vez de 2)
        $table->foreignId('id_usuario')
              ->constrained('usuario')
              ->onDelete('cascade');

        $table->foreignId('id_mascota')
              ->constrained('mascota')
              ->onDelete('cascade');

        // Datos
        $table->dateTime('fecha_solicitud')->useCurrent();
        $table->enum('estado', [
            'pendiente', 'aprobada', 'en_pausa', 'rechazada',
            'completada', 'no_concretada', 'cancelada',
        ])->default('pendiente');
        $table->text('mensaje_solicitante')->nullable();
        $table->text('observaciones_fundacion')->nullable();
        $table->dateTime('fecha_resolucion')->nullable();

        $table->timestamps();

        // Índices
        $table->index(['id_usuario', 'estado']);
        $table->index(['id_mascota', 'estado']);
    });
}
};
