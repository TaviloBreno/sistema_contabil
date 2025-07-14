<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_interno',
        'razao_social',
        'cnpj',
        'fantasia',
        'abertura',
        'natureza_juridica',
        'porte',
        'tipo',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'municipio',
        'uf',
        'cep',
        'telefone',
        'email',
        'regime_tributario',
        'capital_social',
        'situacao',
        'data_situacao',
        'situacao_especial',
        'data_situacao_especial',
        'socios',
        'matriz_id'
    ];

    protected $casts = [
        'socios' => 'array',
    ];

    public function matriz()
    {
        return $this->belongsTo(Empresa::class, 'matriz_id');
    }

    public function filiais()
    {
        return $this->hasMany(Empresa::class, 'matriz_id');
    }

    public function obrigacoes()
    {
        return $this->hasMany(Obrigacao::class);
    }

    public function notasFiscais()
    {
        return $this->hasMany(NotaFiscal::class);
    }
}
