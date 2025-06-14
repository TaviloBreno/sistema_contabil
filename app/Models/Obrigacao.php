<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obrigacao extends Model
{
    use HasFactory;

    protected $table = 'obrigacoes';

    protected $fillable = [
        'empresa_id',
        'tipo',
        'frequencia',
        'data_inicio',
        'data_vencimento',
        'status',
        'observacoes',
        'user_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
