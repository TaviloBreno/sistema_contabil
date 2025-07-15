<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chat extends Model
{
    protected $fillable = [
        'name',
        'type',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário que criou o chat
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relacionamento com as mensagens do chat
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'desc');
    }

    /**
     * Relacionamento com as mensagens mais recentes primeiro
     */
    public function latestMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'desc');
    }

    /**
     * Relacionamento com os participantes do chat
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants')
            ->withPivot(['joined_at', 'left_at', 'last_read_at', 'is_admin'])
            ->withTimestamps();
    }

    /**
     * Relacionamento com os participantes ativos
     */
    public function activeParticipants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants')
            ->wherePivot('left_at', null)
            ->withPivot(['joined_at', 'left_at', 'last_read_at', 'is_admin'])
            ->withTimestamps();
    }

    /**
     * Última mensagem do chat
     */
    public function lastMessage(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->latest();
    }

    /**
     * Verifica se o usuário é participante do chat
     */
    public function hasParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Adiciona um participante ao chat
     */
    public function addParticipant(User $user, bool $isAdmin = false): void
    {
        $this->participants()->attach($user->id, [
            'joined_at' => now(),
            'is_admin' => $isAdmin,
        ]);
    }

    /**
     * Remove um participante do chat
     */
    public function removeParticipant(User $user): void
    {
        $this->participants()->updateExistingPivot($user->id, [
            'left_at' => now(),
        ]);
    }

    /**
     * Marca mensagens como lidas para um usuário
     */
    public function markAsRead(User $user): void
    {
        $this->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);
    }

    /**
     * Conta mensagens não lidas para um usuário
     */
    public function getUnreadCountForUser(User $user): int
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();

        if (!$participant) {
            return 0;
        }

        $lastReadAt = $participant->pivot->last_read_at;

        if (!$lastReadAt) {
            return $this->messages()->count();
        }

        return $this->messages()
            ->where('created_at', '>', $lastReadAt)
            ->where('user_id', '!=', $user->id)
            ->count();
    }

    /**
     * Scope para chats privados
     */
    public function scopePrivate($query)
    {
        return $query->where('type', 'private');
    }

    /**
     * Scope para chats em grupo
     */
    public function scopeGroup($query)
    {
        return $query->where('type', 'group');
    }

    /**
     * Scope para chats de um usuário
     */
    public function scopeForUser($query, User $user)
    {
        return $query->whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->whereNull('left_at');
        });
    }
}
