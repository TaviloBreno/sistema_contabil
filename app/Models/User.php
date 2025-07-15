<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Obrigacao;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'role'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGerente(): bool
    {
        return $this->role === 'gerente';
    }

    public function isOperador(): bool
    {
        return $this->role === 'operador';
    }

    public function obrigacoes()
    {
        return $this->hasMany(Obrigacao::class);
    }

    /**
     * Relacionamento com chats criados pelo usuário
     */
    public function createdChats()
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    /**
     * Relacionamento com chats que o usuário participa
     */
    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_participants')
            ->withPivot(['joined_at', 'left_at', 'last_read_at', 'is_admin'])
            ->withTimestamps();
    }

    /**
     * Relacionamento com chats ativos
     */
    public function activeChats()
    {
        return $this->belongsToMany(Chat::class, 'chat_participants')
            ->wherePivot('left_at', null)
            ->withPivot(['joined_at', 'left_at', 'last_read_at', 'is_admin'])
            ->withTimestamps();
    }

    /**
     * Relacionamento com mensagens enviadas
     */
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Busca ou cria um chat privado entre dois usuários
     */
    public function getPrivateChatWith(User $user)
    {
        // Busca chat existente
        $existingChat = Chat::private()
            ->whereHas('participants', function ($query) {
                $query->where('user_id', $this->id);
            })
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if ($existingChat) {
            return $existingChat;
        }

        // Cria novo chat privado
        $chat = Chat::create([
            'type' => 'private',
            'created_by' => $this->id,
        ]);

        $chat->addParticipant($this);
        $chat->addParticipant($user);

        return $chat;
    }

    /**
     * Conta mensagens não lidas
     */
    public function getUnreadMessagesCount(): int
    {
        return $this->activeChats()
            ->get()
            ->sum(function ($chat) {
                return $chat->getUnreadCountForUser($this);
            });
    }
}
