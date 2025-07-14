<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IntegracaoController extends Controller
{
    public function consultarCnpj(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|min:14|max:18'
        ]);

        $cnpj = preg_replace('/[^0-9]/', '', $request->cnpj);

        try {
            $response = Http::timeout(30)->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'razao_social' => $data['legal_nature'] ?? $data['nome'] ?? '',
                        'nome_fantasia' => $data['alias'] ?? '',
                        'cnpj' => $data['tax_id'] ?? $cnpj,
                        'endereco' => $data['address']['street'] ?? '',
                        'numero' => $data['address']['number'] ?? '',
                        'bairro' => $data['address']['neighborhood'] ?? '',
                        'cidade' => $data['address']['city'] ?? '',
                        'uf' => $data['address']['state'] ?? '',
                        'cep' => $data['address']['zip_code'] ?? '',
                        'telefone' => $data['phone'] ?? '',
                        'email' => $data['email'] ?? '',
                        'atividade_principal' => $data['main_activity']['text'] ?? '',
                        'situacao' => $data['status'] ?? 'ATIVA',
                        'data_abertura' => $data['registration_date'] ?? null,
                    ]
                ]);
            } else {
                throw new \Exception('CNPJ não encontrado ou inválido');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao consultar CNPJ: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao consultar CNPJ: ' . $e->getMessage()
            ], 400);
        }
    }

    public function consultarCep(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|min:8|max:10'
        ]);

        $cep = preg_replace('/[^0-9]/', '', $request->cep);

        try {
            $response = Http::timeout(30)->get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['erro'])) {
                    throw new \Exception('CEP não encontrado');
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'cep' => $data['cep'],
                        'logradouro' => $data['logradouro'],
                        'bairro' => $data['bairro'],
                        'cidade' => $data['localidade'],
                        'uf' => $data['uf'],
                        'complemento' => $data['complemento'] ?? '',
                        'ibge' => $data['ibge'] ?? '',
                        'gia' => $data['gia'] ?? '',
                        'ddd' => $data['ddd'] ?? '',
                        'siafi' => $data['siafi'] ?? '',
                    ]
                ]);
            } else {
                throw new \Exception('CEP não encontrado');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao consultar CEP: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao consultar CEP: ' . $e->getMessage()
            ], 400);
        }
    }

    public function consultarNfe(Request $request)
    {
        $request->validate([
            'chave' => 'required|string|size:44'
        ]);

        try {
            // Simulação de consulta NFe - Em produção, usar API da SEFAZ
            $chave = $request->chave;

            // Validar formato da chave
            if (!preg_match('/^[0-9]{44}$/', $chave)) {
                throw new \Exception('Chave de acesso inválida');
            }

            // Simular consulta
            $nfeData = [
                'chave' => $chave,
                'numero' => substr($chave, 25, 9),
                'serie' => substr($chave, 22, 3),
                'cnpj_emitente' => substr($chave, 6, 14),
                'modelo' => substr($chave, 20, 2),
                'data_emissao' => date('Y-m-d', strtotime(substr($chave, 2, 2) . '-' . substr($chave, 4, 2) . '-20' . substr($chave, 0, 2))),
                'status' => 'AUTORIZADA',
                'protocolo' => '135' . date('YmdHis') . '1234567',
                'valor_total' => rand(10000, 99999) / 100,
            ];

            return response()->json([
                'success' => true,
                'data' => $nfeData
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao consultar NFe: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao consultar NFe: ' . $e->getMessage()
            ], 400);
        }
    }

    public function consultarCotacoes()
    {
        try {
            $response = Http::timeout(30)->get('https://api.exchangerate-api.com/v4/latest/USD');

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'usd_brl' => $data['rates']['BRL'] ?? 5.0,
                        'eur_brl' => ($data['rates']['BRL'] ?? 5.0) / ($data['rates']['EUR'] ?? 0.85),
                        'data_atualizacao' => $data['date'] ?? date('Y-m-d'),
                    ]
                ]);
            } else {
                throw new \Exception('Erro ao buscar cotações');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotações: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao consultar cotações: ' . $e->getMessage()
            ], 400);
        }
    }
}
