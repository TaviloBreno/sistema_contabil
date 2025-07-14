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
        Schema::create('documento_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('nome_original');
            $table->string('nome_arquivo');
            $table->string('tipo_arquivo');
            $table->string('categoria');
            $table->integer('tamanho');
            $table->string('caminho');
            $table->string('hash_arquivo');
            $table->json('tags')->nullable();
            $table->text('descricao')->nullable();

            // Relacionamentos polimórficos
            $table->nullableMorphs('vinculado');

            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();

            $table->index(['categoria', 'empresa_id']);
            $table->index('hash_arquivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_uploads');
    }
};
