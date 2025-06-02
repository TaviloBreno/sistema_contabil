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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_interno')->unique(); // Ex: TDPJ
            $table->string('razao_social');
            $table->string('cnpj')->unique();
            $table->json('socios'); // Array de nomes
            $table->string('regime_tributario');
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('matriz_id')->nullable()->constrained('empresas'); // Para filiais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
