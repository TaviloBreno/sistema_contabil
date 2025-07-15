<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return;
        }

        // Criar alguns chats privados
        $user1 = $users->first();
        $user2 = $users->skip(1)->first();

        $privateChat = Chat::create([
            'type' => 'private',
            'created_by' => $user1->id,
            'last_message_at' => now(),
        ]);

        $privateChat->addParticipant($user1);
        $privateChat->addParticipant($user2);

        // Adicionar algumas mensagens
        ChatMessage::create([
            'chat_id' => $privateChat->id,
            'user_id' => $user1->id,
            'message' => 'Olá! Como você está?',
            'type' => 'text',
        ]);

        ChatMessage::create([
            'chat_id' => $privateChat->id,
            'user_id' => $user2->id,
            'message' => 'Oi! Estou bem, obrigado! E você?',
            'type' => 'text',
        ]);

        ChatMessage::create([
            'chat_id' => $privateChat->id,
            'user_id' => $user1->id,
            'message' => 'Também estou bem! Vamos trabalhar no projeto hoje?',
            'type' => 'text',
        ]);

        // Criar um chat em grupo se tiver mais usuários
        if ($users->count() >= 3) {
            $groupChat = Chat::create([
                'name' => 'Equipe de Desenvolvimento',
                'type' => 'group',
                'created_by' => $user1->id,
                'last_message_at' => now(),
            ]);

            $groupChat->addParticipant($user1, true); // Admin
            $groupChat->addParticipant($user2);

            if ($users->count() >= 3) {
                $user3 = $users->skip(2)->first();
                $groupChat->addParticipant($user3);
            }

            // Adicionar algumas mensagens ao grupo
            ChatMessage::create([
                'chat_id' => $groupChat->id,
                'user_id' => $user1->id,
                'message' => 'Bem-vindos ao grupo da equipe!',
                'type' => 'text',
            ]);

            ChatMessage::create([
                'chat_id' => $groupChat->id,
                'user_id' => $user2->id,
                'message' => 'Obrigado por me adicionar!',
                'type' => 'text',
            ]);

            if ($users->count() >= 3) {
                ChatMessage::create([
                    'chat_id' => $groupChat->id,
                    'user_id' => $users->skip(2)->first()->id,
                    'message' => 'Ótimo! Vamos trabalhar juntos!',
                    'type' => 'text',
                ]);
            }
        }

        // Criar outro chat privado se tiver mais usuários
        if ($users->count() >= 4) {
            $user3 = $users->skip(2)->first();
            $user4 = $users->skip(3)->first();

            $privateChat2 = Chat::create([
                'type' => 'private',
                'created_by' => $user3->id,
                'last_message_at' => now(),
            ]);

            $privateChat2->addParticipant($user3);
            $privateChat2->addParticipant($user4);

            ChatMessage::create([
                'chat_id' => $privateChat2->id,
                'user_id' => $user3->id,
                'message' => 'Oi! Tudo bem?',
                'type' => 'text',
            ]);

            ChatMessage::create([
                'chat_id' => $privateChat2->id,
                'user_id' => $user4->id,
                'message' => 'Tudo ótimo! Como vai o trabalho?',
                'type' => 'text',
            ]);
        }
    }
}
