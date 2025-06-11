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
        // Verifica se a tabela existe
        if (!Schema::hasTable('configuracoes')) {
            $this->command->error('A tabela configuracoes não existe! Execute as migrations primeiro.');
            return;
        }

        $configuracoes = [
            [
                'chave' => 'nome_empresa',
                'valor' => 'Contábil Exemplo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'email_suporte',
                'valor' => 'suporte@contabil.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'telefone',
                'valor' => '(11) 99999-0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($configuracoes as $config) {
            if (!DB::table('configuracoes')->where('chave', $config['chave'])->exists()) {
                DB::table('configuracoes')->insert($config);
            }
        }

        $this->command->info('Configurações do sistema inseridas com sucesso!');
    }
}
