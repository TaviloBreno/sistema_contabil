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
        Schema::create('obrigacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->string('tipo');
            $table->enum('frequencia', ['mensal', 'trimestral', 'anual']);
            $table->date('data_inicio');
            $table->date('data_vencimento');
            $table->date('data_conclusao')->nullable();
            $table->enum('status', ['pendente', 'em andamento', 'concluida'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obrigacoes');
    }
};
