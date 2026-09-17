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
        Schema::table('adopcion', function (Blueprint $table) {
            // Cuándo le toca a la fundación volver a revisar cómo está el
            // animal. Se fija al completar la adopción y se va empujando
            // cada vez que se registra un seguimiento (o se posterga si la
            // fundación ignora el recordatorio).
            $table->dateTime('proximo_seguimiento_at')->nullable()->after('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adopcion', function (Blueprint $table) {
            $table->dropColumn('proximo_seguimiento_at');
        });
    }
};
