<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentoUpload;
use App\Models\Empresa;
use App\Models\User;

class DocumentoUploadSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = Empresa::all();
        $users = User::all();

        if ($empresas->isEmpty() || $users->isEmpty()) {
            return;
        }

        $documentos = [
            [
                'nome_original' => 'Contrato_Prestacao_Servicos_2024.pdf',
                'categoria' => 'contrato',
                'tipo_arquivo' => 'application/pdf',
                'tamanho' => 245760,
                'descricao' => 'Contrato de prestação de serviços contábeis para 2024',
                'tags' => ['contrato', 'prestacao', 'servicos', '2024']
            ],
            [
                'nome_original' => 'Certidao_Regularidade_Fiscal.pdf',
                'categoria' => 'certidao',
                'tipo_arquivo' => 'application/pdf',
                'tamanho' => 180234,
                'descricao' => 'Certidão de regularidade fiscal emitida pela Receita Federal',
                'tags' => ['certidao', 'regularidade', 'fiscal']
            ],
            [
                'nome_original' => 'NFe_35240614200166000187550010000001234567890123.xml',
                'categoria' => 'nfe',
                'tipo_arquivo' => 'application/xml',
                'tamanho' => 45680,
                'descricao' => 'Nota Fiscal Eletrônica de venda de produtos',
                'tags' => ['nfe', 'venda', 'produtos']
            ],
            [
                'nome_original' => 'Licenca_Funcionamento_2024.pdf',
                'categoria' => 'licenca',
                'tipo_arquivo' => 'application/pdf',
                'tamanho' => 156890,
                'descricao' => 'Licença de funcionamento renovada para 2024',
                'tags' => ['licenca', 'funcionamento', '2024']
            ],
            [
                'nome_original' => 'Comprovante_Pagamento_DAS_Janeiro.pdf',
                'categoria' => 'comprovante',
                'tipo_arquivo' => 'application/pdf',
                'tamanho' => 89123,
                'descricao' => 'Comprovante de pagamento do DAS de janeiro 2024',
                'tags' => ['comprovante', 'das', 'janeiro', '2024']
            ],
            [
                'nome_original' => 'Relatorio_Mensal_Faturamento.xlsx',
                'categoria' => 'relatorio',
                'tipo_arquivo' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'tamanho' => 67890,
                'descricao' => 'Relatório mensal de faturamento e vendas',
                'tags' => ['relatorio', 'faturamento', 'vendas']
            ],
            [
                'nome_original' => 'Backup_Sistema_Contabil_2024.zip',
                'categoria' => 'outros',
                'tipo_arquivo' => 'application/zip',
                'tamanho' => 1234567,
                'descricao' => 'Backup completo do sistema contábil',
                'tags' => ['backup', 'sistema', 'contabil']
            ]
        ];

        foreach ($documentos as $index => $docData) {
            $empresa = $empresas->random();
            $user = $users->random();

            DocumentoUpload::create([
                'nome_original' => $docData['nome_original'],
                'nome_arquivo' => 'doc_' . uniqid() . '_' . $index . '.pdf',
                'tipo_arquivo' => $docData['tipo_arquivo'],
                'categoria' => $docData['categoria'],
                'tamanho' => $docData['tamanho'],
                'caminho' => 'documentos/doc_' . uniqid() . '_' . $index . '.pdf',
                'hash_arquivo' => md5($docData['nome_original'] . time()),
                'tags' => $docData['tags'],
                'descricao' => $docData['descricao'],
                'vinculado_type' => null,
                'vinculado_id' => null,
                'empresa_id' => $empresa->id,
                'user_id' => $user->id,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }
}
