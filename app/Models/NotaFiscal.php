<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotaFiscal extends Model
{
    use HasFactory;

    protected $table = 'notas_fiscais';

    protected $fillable = [
        'numero_nf',
        'serie',
        'modelo',
        'tipo',
        'status',
        'empresa_id',
        'destinatario_nome',
        'destinatario_documento',
        'destinatario_endereco',
        'destinatario_cidade',
        'destinatario_uf',
        'destinatario_cep',
        'destinatario_telefone',
        'destinatario_email',
        'data_emissao',
        'data_saida',
        'valor_produtos',
        'valor_frete',
        'valor_seguro',
        'valor_desconto',
        'valor_outras_despesas',
        'valor_ipi',
        'valor_icms',
        'valor_pis',
        'valor_cofins',
        'valor_total',
        'chave_acesso',
        'protocolo_autorizacao',
        'data_autorizacao',
        'observacoes',
        'xml_enviado',
        'xml_retorno',
        'motivo_rejeicao',
        'user_id'
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'data_saida' => 'date',
        'data_autorizacao' => 'datetime',
        'valor_produtos' => 'decimal:2',
        'valor_frete' => 'decimal:2',
        'valor_seguro' => 'decimal:2',
        'valor_desconto' => 'decimal:2',
        'valor_outras_despesas' => 'decimal:2',
        'valor_ipi' => 'decimal:2',
        'valor_icms' => 'decimal:2',
        'valor_pis' => 'decimal:2',
        'valor_cofins' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'xml_enviado' => 'array',
        'xml_retorno' => 'array',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemNotaFiscal::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'rascunho' => '<span class="badge badge-secondary">Rascunho</span>',
            'autorizada' => '<span class="badge badge-success">Autorizada</span>',
            'cancelada' => '<span class="badge badge-danger">Cancelada</span>',
            'rejeitada' => '<span class="badge badge-warning">Rejeitada</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Desconhecido</span>';
    }

    public function getTipoBadgeAttribute(): string
    {
        $badges = [
            'entrada' => '<span class="badge badge-info">Entrada</span>',
            'saida' => '<span class="badge badge-primary">Saída</span>',
        ];

        return $badges[$this->tipo] ?? '<span class="badge badge-secondary">Desconhecido</span>';
    }

    public function scopeAutorizadas($query)
    {
        return $query->where('status', 'autorizada');
    }

    public function scopeRascunhos($query)
    {
        return $query->where('status', 'rascunho');
    }

    public function scopeDoMes($query, $mes = null, $ano = null)
    {
        $mes = $mes ?? now()->month;
        $ano = $ano ?? now()->year;

        return $query->whereYear('data_emissao', $ano)
                    ->whereMonth('data_emissao', $mes);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notaFiscal) {
            if (!$notaFiscal->numero_nf) {
                $notaFiscal->numero_nf = static::gerarProximoNumero($notaFiscal->empresa_id, $notaFiscal->serie);
            }
        });
    }

    public static function gerarProximoNumero($empresaId, $serie = '001')
    {
        $ultimaNota = static::where('empresa_id', $empresaId)
            ->where('serie', $serie)
            ->orderBy('numero_nf', 'desc')
            ->first();

        if ($ultimaNota) {
            return str_pad((int)$ultimaNota->numero_nf + 1, 6, '0', STR_PAD_LEFT);
        }

        return '000001';
    }

    public function calcularTotais()
    {
        $this->valor_produtos = $this->itens->sum('valor_total');
        $this->valor_icms = $this->itens->sum('icms_valor');
        $this->valor_ipi = $this->itens->sum('ipi_valor');
        $this->valor_pis = $this->itens->sum('pis_valor');
        $this->valor_cofins = $this->itens->sum('cofins_valor');

        $this->valor_total = $this->valor_produtos
            + $this->valor_frete
            + $this->valor_seguro
            + $this->valor_outras_despesas
            + $this->valor_ipi
            - $this->valor_desconto;
    }
}
