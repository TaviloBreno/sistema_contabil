<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('documentos')) {
            Schema::create('documentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
                $table->foreignId('obrigacao_id')->nullable()->constrained('obrigacoes')->onDelete('set null');
                $table->string('nome_arquivo');
                $table->string('caminho_arquivo');
                $table->string('protocolo')->unique();
                $table->timestamps();
            });

            // Adiciona comentário para documentação
            DB::statement("ALTER TABLE documentos COMMENT 'Tabela de armazenamento de documentos do sistema'");
        } else {
            echo "A tabela documentos já existe. Nenhuma alteração foi realizada.\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cuidado ao dropar tabelas em produção
        if (app()->environment('local', 'testing')) {
            Schema::dropIfExists('documentos');
        }
    }
};
