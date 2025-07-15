@extends('app')

@section('title', 'Chat - ' . ($chat->type === 'private' ? $chat->participants->where('id', '!=', auth()->id())->first()->name ?? 'Conversa' : $chat->name))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comments"></i> Conversas
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('chat.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nova Conversa
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="direct-chat-contacts">
                        @foreach(auth()->user()->chats()->with(['participants', 'messages' => function($query) { $query->latest()->limit(1); }])->get() as $chatItem)
                            <div class="chat-item border-bottom p-3 {{ $chatItem->id === $chat->id ? 'bg-primary text-white' : '' }}"
                                 data-chat-id="{{ $chatItem->id }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            @if($chatItem->type === 'private')
                                                @php
                                                    $otherUser = $chatItem->participants->where('id', '!=', auth()->id())->first();
                                                @endphp
                                                <i class="fas fa-user"></i>
                                                {{ $otherUser ? $otherUser->name : 'Usuário desconhecido' }}
                                            @else
                                                <i class="fas fa-users"></i>
                                                {{ $chatItem->name }}
                                            @endif
                                        </h6>                        @if($chatItem->messages->isNotEmpty())
                            @php $lastMessage = $chatItem->messages->first(); @endphp
                            <small class="{{ $chatItem->id === $chat->id ? 'text-light' : 'text-muted' }}">
                                <strong>{{ $lastMessage->user->name }}:</strong>
                                @if($lastMessage->type === 'text')
                                    {{ Str::limit($lastMessage->message, 50) }}
                                @elseif($lastMessage->type === 'image')
                                    <i class="fas fa-image"></i> Imagem
                                @else
                                    <i class="fas fa-file"></i> Arquivo
                                @endif
                            </small>
                        @else
                            <small class="{{ $chatItem->id === $chat->id ? 'text-light' : 'text-muted' }}">Nenhuma mensagem ainda</small>
                        @endif
                                    </div>                    <div class="text-right">
                        @if($chatItem->messages->isNotEmpty())
                            <small class="{{ $chatItem->id === $chat->id ? 'text-light' : 'text-muted' }}">
                                {{ $chatItem->messages->first()->created_at->diffForHumans() }}
                            </small>
                        @endif
                        @php
                            $unreadCount = $chatItem->getUnreadCountForUser(auth()->user());
                        @endphp
                        @if($unreadCount > 0 && $chatItem->id !== $chat->id)
                            <div class="badge badge-danger">{{ $unreadCount }}</div>
                        @endif
                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        @if($chat->type === 'private')
                            @php
                                $otherUser = $chat->participants->where('id', '!=', auth()->id())->first();
                            @endphp
                            <i class="fas fa-user text-primary"></i>
                            {{ $otherUser ? $otherUser->name : 'Usuário desconhecido' }}
                        @else
                            <i class="fas fa-users text-success"></i>
                            {{ $chat->name }}
                        @endif
                    </h3>
                    <div class="card-tools">
                        @if($chat->type === 'group')
                            <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#participantsModal">
                                <i class="fas fa-users"></i> Participantes
                            </button>
                            <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#editChatModal">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button type="button" class="btn btn-tool text-danger" onclick="leaveChat()">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </button>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="direct-chat-messages" id="chat-messages" style="height: 400px; overflow-y: auto;">
                        @forelse($messages as $message)
                            <div class="direct-chat-msg {{ $message->user_id === auth()->id() ? 'right' : '' }}">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name {{ $message->user_id === auth()->id() ? 'float-right' : 'float-left' }}">
                                        {{ $message->user->name }}
                                    </span>
                                    <span class="direct-chat-timestamp {{ $message->user_id === auth()->id() ? 'float-left' : 'float-right' }}">
                                        {{ $message->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <img class="direct-chat-img" src="{{ asset('adminlte/assets/img/user2-160x160.jpg') }}" alt="Message User Image">
                                <div class="direct-chat-text">
                                    @if($message->type === 'text')
                                        {{ $message->message }}
                                    @elseif($message->type === 'image')
                                        <div class="mb-2">
                                            <img src="{{ $message->getFileUrl() }}" alt="Imagem" class="img-fluid" style="max-height: 200px;">
                                        </div>
                                        @if($message->message)
                                            {{ $message->message }}
                                        @endif
                                    @elseif($message->type === 'file')
                                        <div class="mb-2">
                                            <a href="{{ $message->getFileUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-download"></i> {{ $message->file_name }}
                                            </a>
                                        </div>
                                        @if($message->message)
                                            {{ $message->message }}
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-comments fa-3x mb-3"></i>
                                <p>Nenhuma mensagem ainda. Seja o primeiro a enviar uma mensagem!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card-footer">
                    <form id="message-form" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="message-input" name="message" placeholder="Digite sua mensagem..." class="form-control">
                            <div class="input-group-append">
                                <label class="btn btn-outline-secondary" for="file-input">
                                    <i class="fas fa-paperclip"></i>
                                </label>
                                <input type="file" id="file-input" name="file" style="display: none;" accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.txt">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">
                            <span id="file-name"></span>
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Participantes -->
@if($chat->type === 'group')
<div class="modal fade" id="participantsModal" tabindex="-1" role="dialog" aria-labelledby="participantsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="participantsModalLabel">
                    <i class="fas fa-users"></i> Participantes
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    @foreach($participants as $participant)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user text-primary"></i>
                                {{ $participant->name }}
                                @if($participant->pivot->is_admin)
                                    <span class="badge badge-warning">Admin</span>
                                @endif
                            </div>
                            <small class="text-muted">
                                Entrou em {{ $participant->pivot->joined_at->format('d/m/Y') }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Chat -->
<div class="modal fade" id="editChatModal" tabindex="-1" role="dialog" aria-labelledby="editChatModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChatModalLabel">
                    <i class="fas fa-edit"></i> Editar Grupo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('chat.update', $chat) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Nome do Grupo:</label>
                        <input type="text" class="form-control" id="edit_name" name="name" value="{{ $chat->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_participants">Participantes:</label>
                        <select class="form-control" id="edit_participants" name="participants[]" multiple>
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}" {{ $participants->contains($user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const chatId = {{ $chat->id }};
    const messagesContainer = $('#chat-messages');

    // Inicializar select múltiplo
    $('#edit_participants').select2({
        placeholder: 'Selecione os participantes...',
        allowClear: true
    });

    // Scroll para o final das mensagens
    scrollToBottom();

    // Enviar mensagem
    $('#message-form').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const messageInput = $('#message-input');
        const fileInput = $('#file-input');

        if (!messageInput.val().trim() && !fileInput[0].files.length) {
            return;
        }

        $.ajax({
            url: `/chat/${chatId}/send-message`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    addMessageToChat(response.message, true);
                    messageInput.val('');
                    fileInput.val('');
                    $('#file-name').text('');
                    scrollToBottom();
                }
            },
            error: function(xhr) {
                console.error('Erro ao enviar mensagem:', xhr.responseText);
                alert('Erro ao enviar mensagem. Tente novamente.');
            }
        });
    });

    // Mostrar nome do arquivo selecionado
    $('#file-input').on('change', function() {
        const fileName = this.files[0] ? this.files[0].name : '';
        $('#file-name').text(fileName);
    });

    // Navegar para outros chats
    $('.chat-item').on('click', function() {
        if ($(this).data('chat-id') !== chatId) {
            const chatId = $(this).data('chat-id');
            window.location.href = `/chat/${chatId}`;
        }
    });

    // Funções auxiliares
    function addMessageToChat(message, isOwn) {
        const messageHtml = `
            <div class="direct-chat-msg ${isOwn ? 'right' : ''}">
                <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name ${isOwn ? 'float-right' : 'float-left'}">
                        ${message.user.name}
                    </span>
                    <span class="direct-chat-timestamp ${isOwn ? 'float-left' : 'float-right'}">
                        ${message.created_at}
                    </span>
                </div>
                <img class="direct-chat-img" src="{{ asset('adminlte/assets/img/user2-160x160.jpg') }}" alt="Message User Image">
                <div class="direct-chat-text">
                    ${formatMessageContent(message)}
                </div>
            </div>
        `;
        messagesContainer.append(messageHtml);
    }

    function formatMessageContent(message) {
        if (message.type === 'text') {
            return message.message;
        } else if (message.type === 'image') {
            return `
                <div class="mb-2">
                    <img src="${message.file_url}" alt="Imagem" class="img-fluid" style="max-height: 200px;">
                </div>
                ${message.message || ''}
            `;
        } else if (message.type === 'file') {
            return `
                <div class="mb-2">
                    <a href="${message.file_url}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="fas fa-download"></i> ${message.file_name}
                    </a>
                </div>
                ${message.message || ''}
            `;
        }
        return message.message;
    }

    function scrollToBottom() {
        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
    }

    // Permitir envio com Enter
    $('#message-input').on('keypress', function(e) {
        if (e.which === 13) {
            $('#message-form').submit();
        }
    });
});

// Função para sair do chat
function leaveChat() {
    if (confirm('Tem certeza que deseja sair deste grupo?')) {
        $.ajax({
            url: `/chat/{{ $chat->id }}/leave`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect_url;
                }
            },
            error: function(xhr) {
                console.error('Erro ao sair do chat:', xhr.responseText);
                alert('Erro ao sair do chat. Tente novamente.');
            }
        });
    }
}
</script>
@endsection
