<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('fantasia')->nullable();
            $table->date('abertura')->nullable();
            $table->string('porte')->nullable();
            $table->string('tipo')->nullable();
            $table->string('natureza_juridica')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('municipio')->nullable();
            $table->string('uf')->nullable();
            $table->string('cep')->nullable();
            $table->string('capital_social')->nullable();
            $table->string('situacao')->nullable();
            $table->date('data_situacao')->nullable();
            $table->string('situacao_especial')->nullable();
            $table->date('data_situacao_especial')->nullable();
            $table->text('atividade_principal')->nullable();
            $table->text('atividades_secundarias')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'fantasia',
                'abertura',
                'porte',
                'tipo',
                'natureza_juridica',
                'logradouro',
                'numero',
                'complemento',
                'bairro',
                'municipio',
                'uf',
                'cep',
                'capital_social',
                'situacao',
                'data_situacao',
                'situacao_especial',
                'data_situacao_especial',
                'atividade_principal',
                'atividades_secundarias',
            ]);
        });
    }
};
