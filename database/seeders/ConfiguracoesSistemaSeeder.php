<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfiguracoesSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('configuracoes')) {
            $this->command->error('A tabela "configuracoes" não existe. Execute as migrations primeiro.');
            return;
        }

        $configuracoes = [
            [
                'chave' => 'nome_empresa',
                'valor' => 'Contábil Exemplo',
                'tipo' => 'text',
                'grupo' => 'empresa',
                'descricao' => 'Nome da empresa exibido no sistema.',
                'ordem' => 1,
            ],
            [
                'chave' => 'email_suporte',
                'valor' => 'suporte@contabil.com',
                'tipo' => 'email',
                'grupo' => 'empresa',
                'descricao' => 'E-mail de suporte técnico ou administrativo.',
                'ordem' => 2,
            ],
            [
                'chave' => 'telefone',
                'valor' => '(11) 99999-0000',
                'tipo' => 'text',
                'grupo' => 'empresa',
                'descricao' => 'Telefone principal de contato da empresa.',
                'ordem' => 3,
            ],
            [
                'chave' => 'mostrar_notificacoes',
                'valor' => 'true',
                'tipo' => 'boolean',
                'grupo' => 'sistema',
                'descricao' => 'Ativa ou desativa alertas e mensagens no painel.',
                'ordem' => 4,
            ],
            [
                'chave' => 'dias_antes_vencimento_alerta',
                'valor' => '5',
                'tipo' => 'number',
                'grupo' => 'sistema',
                'descricao' => 'Dias antes do vencimento para exibir alertas.',
                'ordem' => 5,
            ],
        ];

        foreach ($configuracoes as $index => $config) {
            DB::table('configuracoes')->updateOrInsert(
                ['chave' => $config['chave']],
                array_merge($config, [
                    'updated_at' => now(),
                    'created_at' => now()
                ])
            );
        }

        $this->command->info('✅ Configurações do sistema inseridas/atualizadas com sucesso!');
    }
}
