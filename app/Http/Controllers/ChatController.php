<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $chats = Chat::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['participants', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->withCount(['messages'])
        ->orderBy('last_message_at', 'desc')
        ->get();

        $users = User::where('id', '!=', $user->id)->get();

        return view('chat.index', compact('chats', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('chat.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:private,group',
            'name' => 'required_if:type,group|string|max:255',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
        ]);

        $user = Auth::user();
        $participants = collect($request->participants);

        // Para chat privado, verificar se já existe
        if ($request->type === 'private') {
            if ($participants->count() !== 1) {
                return redirect()->back()->with('error', 'Chat privado deve ter exatamente 1 participante.');
            }

            $otherUser = User::find($participants->first());

            // Buscar chat privado existente
            $existingChat = Chat::where('type', 'private')
                ->whereHas('participants', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereHas('participants', function($query) use ($otherUser) {
                    $query->where('user_id', $otherUser->id);
                })
                ->first();

            if ($existingChat) {
                return redirect()->route('chat.show', $existingChat);
            }
        }

        // Criar novo chat
        $chat = Chat::create([
            'name' => $request->name,
            'type' => $request->type,
            'created_by' => $user->id,
        ]);

        // Adicionar criador como participante
        $chat->addParticipant($user, true);

        // Adicionar outros participantes
        foreach ($participants as $participantId) {
            if ($participantId != $user->id) {
                $participant = User::find($participantId);
                $chat->addParticipant($participant);
            }
        }

        return redirect()->route('chat.show', $chat)
            ->with('success', 'Chat criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        $user = Auth::user();

        // Verificar se o usuário pode acessar o chat
        if (!$chat->hasParticipant($user)) {
            abort(403, 'Você não tem permissão para acessar este chat.');
        }

        // Marcar mensagens como lidas
        $chat->markAsRead($user);

        // Carregar mensagens
        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        $participants = $chat->activeParticipants;
        $allUsers = User::where('id', '!=', $user->id)->get();

        return view('chat.show', compact('chat', 'messages', 'participants', 'allUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        $user = Auth::user();

        // Verificar se o usuário pode editar o chat
        if (!$chat->hasParticipant($user)) {
            abort(403, 'Você não tem permissão para editar este chat.');
        }

        $participants = $chat->activeParticipants;
        $allUsers = User::where('id', '!=', $user->id)->get();

        return view('chat.edit', compact('chat', 'participants', 'allUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        $user = Auth::user();

        // Verificar se o usuário pode editar o chat
        if (!$chat->hasParticipant($user)) {
            abort(403, 'Você não tem permissão para editar este chat.');
        }

        $request->validate([
            'name' => 'required_if:type,group|string|max:255',
            'participants' => 'array',
            'participants.*' => 'exists:users,id',
        ]);

        if ($chat->type === 'group') {
            $chat->update([
                'name' => $request->name,
            ]);
        }

        // Atualizar participantes (apenas para grupos)
        if ($chat->type === 'group' && $request->has('participants')) {
            $currentParticipants = $chat->activeParticipants->pluck('id');
            $newParticipants = collect($request->participants);

            // Remover participantes
            $toRemove = $currentParticipants->diff($newParticipants);
            foreach ($toRemove as $userId) {
                $participant = User::find($userId);
                $chat->removeParticipant($participant);
            }

            // Adicionar novos participantes
            $toAdd = $newParticipants->diff($currentParticipants);
            foreach ($toAdd as $userId) {
                $participant = User::find($userId);
                $chat->addParticipant($participant);
            }
        }

        return redirect()->route('chat.show', $chat)
            ->with('success', 'Chat atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        $user = Auth::user();

        // Verificar se o usuário pode deletar o chat
        if ($chat->created_by !== $user->id) {
            abort(403, 'Apenas o criador do chat pode deletá-lo.');
        }

        $chat->delete();

        return redirect()->route('chat.index')
            ->with('success', 'Chat deletado com sucesso!');
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, Chat $chat): JsonResponse
    {
        $user = Auth::user();

        // Verificar se o usuário pode enviar mensagens
        if (!$chat->hasParticipant($user)) {
            return response()->json(['error' => 'Você não tem permissão para enviar mensagens neste chat.'], 403);
        }

        $request->validate([
            'message' => 'required_without:file|string|max:1000',
            'file' => 'nullable|file|max:10240', // 10MB
        ]);

        $messageData = [
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->message ?? '',
            'type' => 'text',
        ];

        // Processar arquivo se enviado
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat-files', $filename, 'public');

            $messageData['file_path'] = $path;
            $messageData['file_name'] = $file->getClientOriginalName();
            $messageData['type'] = $this->getFileType($file->getClientMimeType());
        }

        $message = ChatMessage::create($messageData);

        // Atualizar última mensagem do chat
        $chat->update(['last_message_at' => now()]);

        // Retornar dados da mensagem
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'created_at' => $message->created_at->format('H:i'),
                'type' => $message->type,
                'file_name' => $message->file_name,
                'file_url' => $message->getFileUrl(),
            ],
        ]);
    }

    /**
     * Get messages for a chat
     */
    public function getMessages(Chat $chat): JsonResponse
    {
        $user = Auth::user();

        // Verificar se o usuário pode ver as mensagens
        if (!$chat->hasParticipant($user)) {
            return response()->json(['error' => 'Você não tem permissão para ver as mensagens deste chat.'], 403);
        }

        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                    ],
                    'created_at' => $message->created_at->format('H:i'),
                    'type' => $message->type,
                    'file_name' => $message->file_name,
                    'file_url' => $message->getFileUrl(),
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    /**
     * Mark chat as read
     */
    public function markAsRead(Chat $chat): JsonResponse
    {
        $user = Auth::user();

        if (!$chat->hasParticipant($user)) {
            return response()->json(['error' => 'Você não tem permissão para marcar este chat como lido.'], 403);
        }

        $chat->markAsRead($user);

        return response()->json(['success' => true]);
    }

    /**
     * Get private chat with user
     */
    public function getPrivateChat(User $user): JsonResponse
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return response()->json(['error' => 'Você não pode iniciar um chat consigo mesmo.'], 400);
        }

        // Buscar chat privado existente
        $chat = Chat::where('type', 'private')
            ->whereHas('participants', function($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
            ->whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        // Se não existir, criar novo
        if (!$chat) {
            $chat = Chat::create([
                'type' => 'private',
                'created_by' => $currentUser->id,
            ]);

            $chat->addParticipant($currentUser);
            $chat->addParticipant($user);
        }

        return response()->json([
            'success' => true,
            'chat_id' => $chat->id,
            'redirect_url' => route('chat.show', $chat),
        ]);
    }

    /**
     * Leave chat
     */
    public function leave(Chat $chat): JsonResponse
    {
        $user = Auth::user();

        if (!$chat->hasParticipant($user)) {
            return response()->json(['error' => 'Você não está neste chat.'], 403);
        }

        if ($chat->type === 'private') {
            return response()->json(['error' => 'Você não pode sair de um chat privado.'], 400);
        }

        $chat->removeParticipant($user);

        return response()->json([
            'success' => true,
            'message' => 'Você saiu do chat.',
            'redirect_url' => route('chat.index'),
        ]);
    }

    /**
     * Get file type based on mime type
     */
    private function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        return 'file';
    }
}
