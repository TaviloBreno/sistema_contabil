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
        Schema::create('notas_fiscais', function (Blueprint $table) {
            $table->id();
            $table->string('numero_nf', 20)->unique();
            $table->string('serie', 5)->default('001');
            $table->string('modelo', 5)->default('55'); // NFe = 55, NFCe = 65
            $table->enum('tipo', ['entrada', 'saida'])->default('saida');
            $table->enum('status', ['rascunho', 'autorizada', 'cancelada', 'rejeitada'])->default('rascunho');

            // Dados do emitente
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');

            // Dados do destinatário
            $table->string('destinatario_nome');
            $table->string('destinatario_documento'); // CPF ou CNPJ
            $table->string('destinatario_endereco')->nullable();
            $table->string('destinatario_cidade')->nullable();
            $table->string('destinatario_uf', 2)->nullable();
            $table->string('destinatario_cep', 10)->nullable();
            $table->string('destinatario_telefone')->nullable();
            $table->string('destinatario_email')->nullable();

            // Dados da nota
            $table->date('data_emissao');
            $table->date('data_saida')->nullable();
            $table->decimal('valor_produtos', 12, 2)->default(0);
            $table->decimal('valor_frete', 12, 2)->default(0);
            $table->decimal('valor_seguro', 12, 2)->default(0);
            $table->decimal('valor_desconto', 12, 2)->default(0);
            $table->decimal('valor_outras_despesas', 12, 2)->default(0);
            $table->decimal('valor_ipi', 12, 2)->default(0);
            $table->decimal('valor_icms', 12, 2)->default(0);
            $table->decimal('valor_pis', 12, 2)->default(0);
            $table->decimal('valor_cofins', 12, 2)->default(0);
            $table->decimal('valor_total', 12, 2);

            // Dados de controle
            $table->string('chave_acesso', 44)->nullable()->unique();
            $table->string('protocolo_autorizacao')->nullable();
            $table->timestamp('data_autorizacao')->nullable();
            $table->text('observacoes')->nullable();

            // Dados SEFAZ
            $table->json('xml_enviado')->nullable();
            $table->json('xml_retorno')->nullable();
            $table->text('motivo_rejeicao')->nullable();

            $table->foreignId('user_id')->constrained('users'); // Usuário que criou
            $table->timestamps();

            $table->index(['empresa_id', 'data_emissao']);
            $table->index(['numero_nf', 'serie']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_fiscais');
    }
};
