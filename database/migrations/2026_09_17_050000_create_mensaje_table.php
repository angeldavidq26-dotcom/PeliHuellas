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

        Schema::create('mensaje', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_solicitud')->index();
            $table->foreign('id_solicitud')->references('id_solicitud')->on('solicitud_adopcion')
                  ->cascadeOnDelete();

            // El remitente siempre es un usuario.id_usuario: el adoptante
            // (solicitud.id_usuario) o el dueño de la fundación
            // (mascota.fundacion.id_usuario) — los dos únicos participantes
            // de la conversación de esa solicitud.
            $table->unsignedBigInteger('id_usuario_remitente')->index();
            $table->foreign('id_usuario_remitente')->references('id_usuario')->on('usuario');

            $table->text('cuerpo');
            $table->dateTime('fecha_envio')->useCurrent();
            $table->dateTime('leido_at')->nullable();

            $table->index(['id_solicitud', 'fecha_envio']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensaje');
    }
};
