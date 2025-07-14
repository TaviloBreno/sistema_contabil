<?php

namespace Database\Seeders;

use App\Models\NotaFiscal;
use App\Models\ItemNotaFiscal;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotaFiscalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = Empresa::all();
        $user = User::first();

        if ($empresas->count() === 0) {
            echo "Nenhuma empresa encontrada. Execute o EmpresaSeeder primeiro.\n";
            return;
        }

        if (!$user) {
            echo "Nenhum usuário encontrado. Execute o UserSeeder primeiro.\n";
            return;
        }

        // Criar algumas notas fiscais de exemplo
        $notasFiscais = [
            [
                'numero_nf' => '000001',
                'empresa_id' => $empresas->first()->id,
                'tipo' => 'saida',
                'destinatario_nome' => 'Cliente Exemplo Ltda',
                'destinatario_documento' => '12.345.678/0001-90',
                'destinatario_endereco' => 'Rua das Flores, 123',
                'destinatario_cidade' => 'São Paulo',
                'destinatario_uf' => 'SP',
                'destinatario_cep' => '01234-567',
                'destinatario_email' => 'cliente@exemplo.com',
                'data_emissao' => now()->subDays(5),
                'status' => 'autorizada',
                'itens' => [
                    [
                        'codigo_produto' => 'PROD001',
                        'descricao' => 'Produto de Exemplo 1',
                        'cfop' => '5102',
                        'unidade' => 'UN',
                        'quantidade' => 2,
                        'valor_unitario' => 150.00,
                        'icms_aliquota' => 18.00,
                    ],
                    [
                        'codigo_produto' => 'PROD002',
                        'descricao' => 'Serviço de Consultoria',
                        'cfop' => '5101',
                        'unidade' => 'HR',
                        'quantidade' => 10,
                        'valor_unitario' => 80.00,
                        'icms_aliquota' => 0.00,
                    ]
                ]
            ],
            [
                'numero_nf' => '000002',
                'empresa_id' => $empresas->count() > 1 ? $empresas->skip(1)->first()->id : $empresas->first()->id,
                'tipo' => 'saida',
                'destinatario_nome' => 'João da Silva',
                'destinatario_documento' => '123.456.789-01',
                'destinatario_endereco' => 'Av. Paulista, 1000',
                'destinatario_cidade' => 'São Paulo',
                'destinatario_uf' => 'SP',
                'destinatario_cep' => '01310-100',
                'data_emissao' => now()->subDays(2),
                'status' => 'rascunho',
                'itens' => [
                    [
                        'codigo_produto' => 'SERV001',
                        'descricao' => 'Desenvolvimento de Software',
                        'cfop' => '5101',
                        'unidade' => 'UN',
                        'quantidade' => 1,
                        'valor_unitario' => 2500.00,
                        'icms_aliquota' => 0.00,
                    ]
                ]
            ],
            [
                'numero_nf' => '000003',
                'empresa_id' => $empresas->first()->id,
                'tipo' => 'entrada',
                'destinatario_nome' => 'Fornecedor ABC Ltda',
                'destinatario_documento' => '98.765.432/0001-10',
                'destinatario_endereco' => 'Rua dos Fornecedores, 456',
                'destinatario_cidade' => 'Rio de Janeiro',
                'destinatario_uf' => 'RJ',
                'destinatario_cep' => '20000-000',
                'data_emissao' => now()->subDays(1),
                'status' => 'autorizada',
                'itens' => [
                    [
                        'codigo_produto' => 'MAT001',
                        'descricao' => 'Material de Escritório',
                        'cfop' => '1102',
                        'unidade' => 'UN',
                        'quantidade' => 5,
                        'valor_unitario' => 45.50,
                        'icms_aliquota' => 18.00,
                    ],
                    [
                        'codigo_produto' => 'MAT002',
                        'descricao' => 'Equipamento de Informática',
                        'cfop' => '1102',
                        'unidade' => 'UN',
                        'quantidade' => 1,
                        'valor_unitario' => 1200.00,
                        'icms_aliquota' => 18.00,
                    ]
                ]
            ]
        ];

        foreach ($notasFiscais as $notaData) {
            $itens = $notaData['itens'];
            unset($notaData['itens']);

            $notaData['user_id'] = $user->id;
            $notaData['valor_total'] = 0; // Valor inicial, será recalculado

            $notaFiscal = NotaFiscal::create($notaData);

            foreach ($itens as $itemData) {
                $valorTotal = $itemData['quantidade'] * $itemData['valor_unitario'];

                $item = ItemNotaFiscal::create([
                    'nota_fiscal_id' => $notaFiscal->id,
                    'codigo_produto' => $itemData['codigo_produto'],
                    'descricao' => $itemData['descricao'],
                    'cfop' => $itemData['cfop'],
                    'unidade' => $itemData['unidade'],
                    'quantidade' => $itemData['quantidade'],
                    'valor_unitario' => $itemData['valor_unitario'],
                    'valor_total' => $valorTotal,
                    'icms_aliquota' => $itemData['icms_aliquota'],
                ]);

                $item->calcularImpostos();
                $item->save();
            }

            // Simular autorização para notas autorizadas
            if ($notaData['status'] === 'autorizada') {
                $notaFiscal->update([
                    'chave_acesso' => $this->gerarChaveAcesso($notaFiscal),
                    'protocolo_autorizacao' => 'PROT' . now()->format('YmdHis') . rand(1000, 9999),
                    'data_autorizacao' => $notaFiscal->data_emissao->addHours(2),
                ]);
            }

            $notaFiscal->calcularTotais();
            $notaFiscal->save();
        }

        echo "Notas fiscais de exemplo criadas com sucesso!\n";
    }

    private function gerarChaveAcesso(NotaFiscal $notaFiscal): string
    {
        $empresa = $notaFiscal->empresa;
        $cnpj = preg_replace('/\D/', '', $empresa->cnpj);
        $dataEmissao = $notaFiscal->data_emissao->format('ymd');
        $serie = str_pad($notaFiscal->serie, 3, '0', STR_PAD_LEFT);
        $numero = str_pad($notaFiscal->numero_nf, 9, '0', STR_PAD_LEFT);

        $chave = $cnpj . $notaFiscal->modelo . $serie . $numero . $dataEmissao . rand(10000000, 99999999);

        return $chave . $this->calcularDV($chave);
    }

    private function calcularDV(string $chave): string
    {
        $soma = 0;
        $peso = 2;

        for ($i = strlen($chave) - 1; $i >= 0; $i--) {
            $soma += $chave[$i] * $peso;
            $peso = $peso == 9 ? 2 : $peso + 1;
        }

        $resto = $soma % 11;
        return $resto < 2 ? '0' : (string)(11 - $resto);
    }
}
