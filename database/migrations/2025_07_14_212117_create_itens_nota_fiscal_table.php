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
        Schema::create('itens_nota_fiscal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('notas_fiscais')->onDelete('cascade');
            $table->integer('numero_item');

            // Dados do produto/serviço
            $table->string('codigo_produto', 50);
            $table->string('descricao');
            $table->string('ncm', 10)->nullable(); // Nomenclatura Comum do Mercosul
            $table->string('cfop', 4); // Código Fiscal de Operações e Prestações
            $table->string('unidade', 10)->default('UN');

            // Quantidades e valores
            $table->decimal('quantidade', 12, 4);
            $table->decimal('valor_unitario', 12, 4);
            $table->decimal('valor_total', 12, 2);
            $table->decimal('valor_desconto', 12, 2)->default(0);
            $table->decimal('valor_frete', 12, 2)->default(0);
            $table->decimal('valor_seguro', 12, 2)->default(0);
            $table->decimal('valor_outras_despesas', 12, 2)->default(0);

            // Impostos
            // ICMS
            $table->string('icms_cst', 3)->nullable(); // Código de Situação Tributária
            $table->decimal('icms_base_calculo', 12, 2)->default(0);
            $table->decimal('icms_aliquota', 5, 2)->default(0);
            $table->decimal('icms_valor', 12, 2)->default(0);

            // IPI
            $table->string('ipi_cst', 2)->nullable();
            $table->decimal('ipi_base_calculo', 12, 2)->default(0);
            $table->decimal('ipi_aliquota', 5, 2)->default(0);
            $table->decimal('ipi_valor', 12, 2)->default(0);

            // PIS
            $table->string('pis_cst', 2)->nullable();
            $table->decimal('pis_base_calculo', 12, 2)->default(0);
            $table->decimal('pis_aliquota', 5, 4)->default(0);
            $table->decimal('pis_valor', 12, 2)->default(0);

            // COFINS
            $table->string('cofins_cst', 2)->nullable();
            $table->decimal('cofins_base_calculo', 12, 2)->default(0);
            $table->decimal('cofins_aliquota', 5, 4)->default(0);
            $table->decimal('cofins_valor', 12, 2)->default(0);

            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['nota_fiscal_id', 'numero_item']);
            $table->index('codigo_produto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_nota_fiscal');
    }
};
