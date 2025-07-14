<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes';

    protected $fillable = [
        'chave',
        'valor',
        'descricao',
        'tipo',
        'grupo',
        'ordem',
    ];

    public $timestamps = true;

    /**
     * Ordenação padrão: por grupo e ordem.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('ordenado', function ($query) {
            $query->orderBy('grupo')->orderBy('ordem');
        });
    }

    /**
     * Retorna um nome amigável da chave para exibição.
     */
    public function getLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->chave));
    }
}
