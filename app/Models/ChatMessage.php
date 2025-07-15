<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ChatMessage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'type',
        'file_path',
        'file_name',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relacionamento com o chat
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relacionamento com o usuário que enviou a mensagem
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para mensagens não lidas
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope para mensagens de texto
     */
    public function scopeText($query)
    {
        return $query->where('type', 'text');
    }

    /**
     * Scope para mensagens com arquivo
     */
    public function scopeFile($query)
    {
        return $query->where('type', 'file');
    }

    /**
     * Scope para mensagens com imagem
     */
    public function scopeImage($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Marca a mensagem como lida
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Verifica se a mensagem tem arquivo
     */
    public function hasFile(): bool
    {
        return in_array($this->type, ['file', 'image']) && !empty($this->file_path);
    }

    /**
     * Retorna a URL do arquivo
     */
    public function getFileUrl(): ?string
    {
        if (!$this->hasFile()) {
            return null;
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Retorna o tamanho do arquivo formatado
     */
    public function getFileSizeFormatted(): ?string
    {
        if (!$this->hasFile()) {
            return null;
        }

        $filePath = storage_path('app/public/' . $this->file_path);

        if (!file_exists($filePath)) {
            return null;
        }

        $size = filesize($filePath);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unit = 0;

        while ($size > 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Verifica se é uma imagem
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Verifica se é um arquivo
     */
    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    /**
     * Configuração do log de atividades
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['message', 'type'])
            ->setDescriptionForEvent(fn(string $eventName) => "Mensagem {$eventName}")
            ->useLogName('chat');
    }
}
