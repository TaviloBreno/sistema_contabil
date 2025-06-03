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
        'socios',
        'regime_tributario',
        'telefone',
        'email',
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
}
