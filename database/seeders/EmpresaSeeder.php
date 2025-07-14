<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = [
            [
                'codigo_interno' => 'EMP001',
                'razao_social' => 'Empresa Exemplo Ltda',
                'cnpj' => '12.345.678/0001-90',
                'socios' => ['João Silva', 'Maria Santos'],
                'regime_tributario' => 'Simples Nacional',
                'telefone' => '(11) 99999-9999',
                'email' => 'contato@exemplo.com.br',
                'matriz_id' => null,
            ],
            [
                'codigo_interno' => 'EMP002',
                'razao_social' => 'Tech Solutions Comércio e Serviços Ltda',
                'cnpj' => '98.765.432/0001-10',
                'socios' => ['Pedro Costa', 'Ana Oliveira'],
                'regime_tributario' => 'Lucro Presumido',
                'telefone' => '(11) 88888-8888',
                'email' => 'admin@techsolutions.com.br',
                'matriz_id' => null,
            ],
            [
                'codigo_interno' => 'EMP003',
                'razao_social' => 'Consultoria Financeira ABC Ltda',
                'cnpj' => '11.222.333/0001-44',
                'socios' => ['Carlos Mendes'],
                'regime_tributario' => 'Lucro Real',
                'telefone' => '(11) 77777-7777',
                'email' => 'contato@consultoriaabc.com.br',
                'matriz_id' => null,
            ]
        ];

        foreach ($empresas as $empresaData) {
            Empresa::create($empresaData);
        }

        echo "Empresas criadas com sucesso!\n";
    }
}
