<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'codigo_interno' => 'required|string|max:20',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|size:14|unique:empresas,cnpj',
            'fantasia' => 'nullable|string|max:255',
            'abertura' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'regime_tributario' => 'nullable|string|max:255',
            'porte' => 'nullable|string|max:50',
            'tipo' => 'nullable|string|max:50',
            'natureza_juridica' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'nullable|string|max:100',
            'municipio' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:20',
            'capital_social' => 'nullable|string|max:100',
            'situacao' => 'nullable|string|max:100',
            'data_situacao' => 'nullable|date',
            'situacao_especial' => 'nullable|string|max:100',
            'data_situacao_especial' => 'nullable|date',
            'socios' => 'nullable|string|max:1000',
            'matriz_id' => 'nullable|exists:empresas,id',
        ];
    }
}
