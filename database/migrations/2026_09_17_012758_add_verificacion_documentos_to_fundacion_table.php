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
        Schema::table('fundacion', function (Blueprint $table) {
            $table->string('documento_certificado_url', 500)->nullable()->after('logo_url');
            $table->string('documento_representante_url', 500)->nullable()->after('documento_certificado_url');
            $table->dateTime('documentos_enviados_at')->nullable()->after('documento_representante_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fundacion', function (Blueprint $table) {
            $table->dropColumn(['documento_certificado_url', 'documento_representante_url', 'documentos_enviados_at']);
        });
    }
};
