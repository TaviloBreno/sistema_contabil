<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemNotaFiscal extends Model
{
    use HasFactory;

    protected $table = 'itens_nota_fiscal';

    protected $fillable = [
        'nota_fiscal_id',
        'numero_item',
        'codigo_produto',
        'descricao',
        'ncm',
        'cfop',
        'unidade',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'valor_desconto',
        'valor_frete',
        'valor_seguro',
        'valor_outras_despesas',
        'icms_cst',
        'icms_base_calculo',
        'icms_aliquota',
        'icms_valor',
        'ipi_cst',
        'ipi_base_calculo',
        'ipi_aliquota',
        'ipi_valor',
        'pis_cst',
        'pis_base_calculo',
        'pis_aliquota',
        'pis_valor',
        'cofins_cst',
        'cofins_base_calculo',
        'cofins_aliquota',
        'cofins_valor',
        'observacoes'
    ];

    protected $casts = [
        'quantidade' => 'decimal:4',
        'valor_unitario' => 'decimal:4',
        'valor_total' => 'decimal:2',
        'valor_desconto' => 'decimal:2',
        'valor_frete' => 'decimal:2',
        'valor_seguro' => 'decimal:2',
        'valor_outras_despesas' => 'decimal:2',
        'icms_base_calculo' => 'decimal:2',
        'icms_aliquota' => 'decimal:2',
        'icms_valor' => 'decimal:2',
        'ipi_base_calculo' => 'decimal:2',
        'ipi_aliquota' => 'decimal:2',
        'ipi_valor' => 'decimal:2',
        'pis_base_calculo' => 'decimal:2',
        'pis_aliquota' => 'decimal:4',
        'pis_valor' => 'decimal:2',
        'cofins_base_calculo' => 'decimal:2',
        'cofins_aliquota' => 'decimal:4',
        'cofins_valor' => 'decimal:2',
    ];

    public function notaFiscal(): BelongsTo
    {
        return $this->belongsTo(NotaFiscal::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (!$item->numero_item) {
                $ultimoItem = static::where('nota_fiscal_id', $item->nota_fiscal_id)
                    ->orderBy('numero_item', 'desc')
                    ->first();

                $item->numero_item = $ultimoItem ? $ultimoItem->numero_item + 1 : 1;
            }
        });

        static::saved(function ($item) {
            $item->notaFiscal->calcularTotais();
            $item->notaFiscal->save();
        });

        static::deleted(function ($item) {
            $item->notaFiscal->calcularTotais();
            $item->notaFiscal->save();
        });
    }

    public function calcularImpostos()
    {
        // ICMS
        if ($this->icms_aliquota > 0) {
            $this->icms_base_calculo = $this->valor_total;
            $this->icms_valor = ($this->icms_base_calculo * $this->icms_aliquota) / 100;
        }

        // IPI
        if ($this->ipi_aliquota > 0) {
            $this->ipi_base_calculo = $this->valor_total;
            $this->ipi_valor = ($this->ipi_base_calculo * $this->ipi_aliquota) / 100;
        }

        // PIS
        if ($this->pis_aliquota > 0) {
            $this->pis_base_calculo = $this->valor_total;
            $this->pis_valor = ($this->pis_base_calculo * $this->pis_aliquota) / 100;
        }

        // COFINS
        if ($this->cofins_aliquota > 0) {
            $this->cofins_base_calculo = $this->valor_total;
            $this->cofins_valor = ($this->cofins_base_calculo * $this->cofins_aliquota) / 100;
        }
    }
}
