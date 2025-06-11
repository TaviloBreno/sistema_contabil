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
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });

            // Adiciona comentário para documentação
            DB::statement("ALTER TABLE password_reset_tokens COMMENT 'Tabela para armazenamento de tokens de redefinição de senha'");

            echo "Tabela password_reset_tokens criada com sucesso.\n";
        } else {
            echo "A tabela password_reset_tokens já existe. Nenhuma alteração foi realizada.\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cuidado ao dropar tabelas em produção
        if (app()->environment('local', 'testing')) {
            Schema::dropIfExists('password_reset_tokens');
        } else {
            echo "Operação de drop ignorada em ambiente de produção.\n";
        }
    }
};
