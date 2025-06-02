<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo_interno' => 'required|string|unique:empresas,codigo_interno',
            'razao_social'   => 'required|string|max:255',
            'cnpj'           => 'required|string|size:14|unique:empresas,cnpj',
            'socios'         => 'required|array|min:1',
            'regime_tributario' => 'required|string',
            'telefone'       => 'nullable|string',
            'email'          => 'nullable|email',
            'matriz_id'      => 'nullable|exists:empresas,id',
        ];
    }
}
