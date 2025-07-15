@extends('layouts.app')

@section('title', 'Nova Conversa')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Nova Conversa
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('chat.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <form action="{{ route('chat.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="type">Tipo de Conversa:</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Selecione o tipo</option>
                                <option value="private">Conversa Privada</option>
                                <option value="group">Grupo</option>
                            </select>
                        </div>

                        <div class="form-group" id="name-group" style="display: none;">
                            <label for="name">Nome do Grupo:</label>
                            <input type="text" class="form-control" id="name" name="name" maxlength="255" placeholder="Digite o nome do grupo">
                        </div>

                        <div class="form-group">
                            <label for="participants">Participantes:</label>
                            <select class="form-control" id="participants" name="participants[]" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Para conversa privada, selecione apenas 1 pessoa. Para grupo, selecione quantas pessoas desejar.
                            </small>
                        </div>

                        <div class="alert alert-info" id="private-info" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Conversa Privada:</strong> Selecione apenas um participante para iniciar uma conversa privada.
                        </div>

                        <div class="alert alert-info" id="group-info" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Grupo:</strong> Digite um nome para o grupo e selecione os participantes. Você será automaticamente adicionado como administrador.
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Criar Conversa
                        </button>
                        <a href="{{ route('chat.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('#participants').select2({
        placeholder: 'Selecione os participantes...',
        allowClear: true,
        width: '100%'
    });

    // Controle de campos baseado no tipo
    $('#type').on('change', function() {
        const type = $(this).val();

        if (type === 'private') {
            $('#name-group').hide();
            $('#name').prop('required', false);
            $('#private-info').show();
            $('#group-info').hide();

            // Limitar seleção a 1 participante
            $('#participants').select2('destroy').select2({
                placeholder: 'Selecione 1 participante...',
                allowClear: true,
                width: '100%',
                maximumSelectionLength: 1
            });

        } else if (type === 'group') {
            $('#name-group').show();
            $('#name').prop('required', true);
            $('#private-info').hide();
            $('#group-info').show();

            // Permitir múltiplos participantes
            $('#participants').select2('destroy').select2({
                placeholder: 'Selecione os participantes...',
                allowClear: true,
                width: '100%'
            });

        } else {
            $('#name-group').hide();
            $('#name').prop('required', false);
            $('#private-info').hide();
            $('#group-info').hide();

            $('#participants').select2('destroy').select2({
                placeholder: 'Selecione os participantes...',
                allowClear: true,
                width: '100%'
            });
        }
    });

    // Validação do formulário
    $('form').on('submit', function(e) {
        const type = $('#type').val();
        const participants = $('#participants').val();

        if (type === 'private' && (!participants || participants.length !== 1)) {
            e.preventDefault();
            alert('Para conversa privada, selecione exatamente 1 participante.');
            return false;
        }

        if (type === 'group' && (!participants || participants.length === 0)) {
            e.preventDefault();
            alert('Para grupo, selecione pelo menos 1 participante.');
            return false;
        }

        if (type === 'group' && !$('#name').val().trim()) {
            e.preventDefault();
            alert('Digite um nome para o grupo.');
            $('#name').focus();
            return false;
        }
    });
});
</script>
@endsection
