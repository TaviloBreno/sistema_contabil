<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', 'obrigacao_id', 'nome_original', 'caminho', 'protocolo'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function obrigacao()
    {
        return $this->belongsTo(Obrigacao::class);
    }
}
