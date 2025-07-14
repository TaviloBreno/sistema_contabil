<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DocumentoUpload extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'documento_uploads';

    protected $fillable = [
        'nome_original',
        'nome_arquivo',
        'tipo_arquivo',
        'categoria',
        'tamanho',
        'caminho',
        'hash_arquivo',
        'tags',
        'descricao',
        'vinculado_type',
        'vinculado_id',
        'empresa_id',
        'user_id'
    ];

    protected $casts = [
        'tags' => 'array',
        'tamanho' => 'integer'
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vinculado(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->caminho);
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIconeAttribute(): string
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf text-danger',
            'xml' => 'fas fa-file-code text-info',
            'xlsx' => 'fas fa-file-excel text-success',
            'xls' => 'fas fa-file-excel text-success',
            'doc' => 'fas fa-file-word text-primary',
            'docx' => 'fas fa-file-word text-primary',
            'jpg' => 'fas fa-file-image text-warning',
            'jpeg' => 'fas fa-file-image text-warning',
            'png' => 'fas fa-file-image text-warning',
            'gif' => 'fas fa-file-image text-warning',
            'zip' => 'fas fa-file-archive text-dark',
            'rar' => 'fas fa-file-archive text-dark',
            'txt' => 'fas fa-file-alt text-secondary',
            'csv' => 'fas fa-file-csv text-success'
        ];

        $extensao = pathinfo($this->nome_original, PATHINFO_EXTENSION);
        return $icons[strtolower($extensao)] ?? 'fas fa-file text-secondary';
    }

    public function getCategoriaDescricaoAttribute(): string
    {
        $categorias = [
            'nfe' => 'Nota Fiscal Eletrônica',
            'contrato' => 'Contratos',
            'certidao' => 'Certidões',
            'licenca' => 'Licenças',
            'comprovante' => 'Comprovantes',
            'relatorio' => 'Relatórios',
            'outros' => 'Outros'
        ];

        return $categorias[$this->categoria] ?? 'Categoria não definida';
    }

    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeVinculado($query, $type, $id)
    {
        return $query->where('vinculado_type', $type)
                    ->where('vinculado_id', $id);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome_original', 'categoria', 'empresa_id', 'vinculado_type', 'vinculado_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($documento) {
            if (Storage::disk('public')->exists($documento->caminho)) {
                Storage::disk('public')->delete($documento->caminho);
            }
        });
    }
}
