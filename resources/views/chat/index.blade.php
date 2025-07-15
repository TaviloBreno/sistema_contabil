@extends('app')

@section('title', 'Chat')

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
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newChatModal">
                            <i class="fas fa-plus"></i> Nova Conversa
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="direct-chat-contacts">
                        @forelse($chats as $chat)
                            <div class="chat-item border-bottom p-3" data-chat-id="{{ $chat->id }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
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
                                        </h6>                        @if($chat->messages->isNotEmpty())
                            @php $lastMessage = $chat->messages->first(); @endphp
                            <small class="text-muted">
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
                            <small class="text-muted">Nenhuma mensagem ainda</small>
                        @endif
                                    </div>                    <div class="text-right">
                        @if($chat->messages->isNotEmpty())
                            <small class="text-muted">
                                {{ $chat->messages->first()->created_at->diffForHumans() }}
                            </small>
                        @endif
                        @php
                            $unreadCount = $chat->getUnreadCountForUser(auth()->user());
                        @endphp
                        @if($unreadCount > 0)
                            <div class="badge badge-danger">{{ $unreadCount }}</div>
                        @endif
                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted">
                                <i class="fas fa-comments-slash mb-2"></i>
                                <p>Nenhuma conversa encontrada</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comment-dots"></i> Selecione uma conversa
                    </h3>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-comments fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">Selecione uma conversa para começar a conversar</h4>
                    <p class="text-muted">Ou inicie uma nova conversa clicando no botão "Nova Conversa"</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nova Conversa -->
<div class="modal fade" id="newChatModal" tabindex="-1" role="dialog" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newChatModalLabel">
                    <i class="fas fa-plus"></i> Nova Conversa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('chat.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="chat_type">Tipo de Conversa:</label>
                        <select class="form-control" id="chat_type" name="type" required>
                            <option value="private">Conversa Privada</option>
                            <option value="group">Grupo</option>
                        </select>
                    </div>

                    <div class="form-group" id="group_name_field" style="display: none;">
                        <label for="group_name">Nome do Grupo:</label>
                        <input type="text" class="form-control" id="group_name" name="name" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label for="participants">Participantes:</label>
                        <select class="form-control" id="participants" name="participants[]" multiple required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Para conversa privada, selecione apenas 1 pessoa. Para grupo, selecione quantas pessoas desejar.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Criar Conversa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Alterar campos baseado no tipo de chat
    $('#chat_type').on('change', function() {
        const type = $(this).val();
        if (type === 'group') {
            $('#group_name_field').show();
            $('#group_name').prop('required', true);
        } else {
            $('#group_name_field').hide();
            $('#group_name').prop('required', false);
        }
    });

    // Inicializar select múltiplo
    $('#participants').select2({
        placeholder: 'Selecione os participantes...',
        allowClear: true
    });

    // Redirecionar para chat ao clicar
    $('.chat-item').on('click', function() {
        const chatId = $(this).data('chat-id');
        window.location.href = `/chat/${chatId}`;
    });

    // Adicionar hover effect
    $('.chat-item').hover(
        function() {
            $(this).addClass('bg-light');
        },
        function() {
            $(this).removeClass('bg-light');
        }
    );
});
</script>
@endsection
