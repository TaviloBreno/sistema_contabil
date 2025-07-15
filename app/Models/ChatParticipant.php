<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    protected $fillable = [
        'chat_id',
        'user_id',
        'joined_at',
        'left_at',
        'last_read_at',
        'is_admin',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'last_read_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    /**
     * Relacionamento com o chat
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relacionamento com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para participantes ativos
     */
    public function scopeActive($query)
    {
        return $query->whereNull('left_at');
    }

    /**
     * Scope para participantes inativos
     */
    public function scopeInactive($query)
    {
        return $query->whereNotNull('left_at');
    }

    /**
     * Scope para administradores
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Verifica se o participante está ativo
     */
    public function isActive(): bool
    {
        return is_null($this->left_at);
    }

    /**
     * Verifica se o participante é administrador
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Sai do chat
     */
    public function leave(): void
    {
        $this->update(['left_at' => now()]);
    }

    /**
     * Entra no chat novamente
     */
    public function rejoin(): void
    {
        $this->update(['left_at' => null]);
    }

    /**
     * Atualiza a última leitura
     */
    public function updateLastRead(): void
    {
        $this->update(['last_read_at' => now()]);
    }
}
