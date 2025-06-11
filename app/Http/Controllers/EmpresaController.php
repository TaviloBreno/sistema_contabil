<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Http\Requests\EmpresaRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::with('matriz')->orderBy('razao_social')->paginate(10);
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create', [
            'matrizes' => Empresa::whereNull('matriz_id')->get()
        ]);
    }

    public function store(EmpresaRequest $request)
    {
        Empresa::create($request->validated());
        return redirect()->route('empresas.index')->with('success', 'Empresa cadastrada com sucesso.');
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', [
            'empresa' => $empresa,
            'matrizes' => Empresa::whereNull('matriz_id')->get()
        ]);
    }

    public function update(EmpresaRequest $request, Empresa $empresa)
    {
        $empresa->update($request->validated());
        return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', 'Empresa excluída com sucesso.');
    }

    public function consultarCNPJ(Request $request): JsonResponse
    {
        $cnpj = preg_replace('/\D/', '', $request->cnpj);

        if (strlen($cnpj) !== 14) {
            return response()->json(['error' => 'CNPJ inválido.'], 422);
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'Mozilla/5.0'
            ])->get("https://receitaws.com.br/v1/cnpj/{$cnpj}");

            if ($response->failed()) {
                return response()->json(['error' => 'Erro ao conectar à API da Receita.'], 400);
            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ERROR') {
                return response()->json(['error' => $data['message'] ?? 'Erro na consulta do CNPJ.'], 400);
            }

            return response()->json([
                'status'                 => $data['status'] ?? '',
                'cnpj'                   => $data['cnpj'] ?? '',
                'nome'                   => $data['nome'] ?? '',
                'fantasia'               => $data['fantasia'] ?? '',
                'abertura'              => $data['abertura'] ?? '',
                'telefone'              => $data['telefone'] ?? '',
                'email'                 => $data['email'] ?? '',
                'porte'                 => $data['porte'] ?? '',
                'tipo'                  => $data['tipo'] ?? '',
                'natureza_juridica'     => $data['natureza_juridica'] ?? '',
                'logradouro'            => $data['logradouro'] ?? '',
                'numero'                => $data['numero'] ?? '',
                'complemento'           => $data['complemento'] ?? '',
                'bairro'                => $data['bairro'] ?? '',
                'municipio'             => $data['municipio'] ?? '',
                'uf'                    => $data['uf'] ?? '',
                'cep'                   => $data['cep'] ?? '',
                'capital_social'        => $data['capital_social'] ?? '',
                'situacao'              => $data['situacao'] ?? '',
                'data_situacao'         => $data['data_situacao'] ?? '',
                'situacao_especial'     => $data['situacao_especial'] ?? '',
                'data_situacao_especial' => $data['data_situacao_especial'] ?? '',
                'atividade_principal'   => $data['atividade_principal'][0]['text'] ?? '',
                'atividades_secundarias' => collect($data['atividades_secundarias'] ?? [])->pluck('text')->implode(', '),
                'socios'                => collect($data['qsa'] ?? [])->pluck('nome')->implode(', ')
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao consultar CNPJ: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao buscar dados do CNPJ.'], 500);
        }
    }
}
