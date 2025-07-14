@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Novo Documento</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documentos.index') }}">Documentos</a></li>
                    <li class="breadcrumb-item active">Novo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Enviar Documento</h3>
                    </div>
                    <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="arquivo">Arquivo <span class="text-danger">*</span></label>
                                        <input type="file" name="arquivo" class="form-control @error('arquivo') is-invalid @enderror" required>
                                        @error('arquivo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Tipos permitidos: PDF, XML, Excel, Word, Imagens, ZIP, TXT, CSV (Max: 10MB)
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="categoria">Categoria <span class="text-danger">*</span></label>
                                        <select name="categoria" class="form-control @error('categoria') is-invalid @enderror" required>
                                            <option value="">Selecione...</option>
                                            @foreach($categorias as $key => $nome)
                                                <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
                                                    {{ $nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categoria')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="empresa_id">Empresa <span class="text-danger">*</span></label>
                                        <select name="empresa_id" class="form-control @error('empresa_id') is-invalid @enderror" required>
                                            <option value="">Selecione uma empresa...</option>
                                            @foreach($empresas as $empresa)
                                                <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                                    {{ $empresa->razao_social }} - {{ $empresa->cnpj }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('empresa_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <textarea name="descricao" class="form-control @error('descricao') is-invalid @enderror" rows="3" placeholder="Descrição do documento...">{{ old('descricao') }}</textarea>
                                        @error('descricao')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tags">Tags</label>
                                        <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
                                               placeholder="Ex: fiscal, contrato, licença (separadas por vírgula)" value="{{ old('tags') }}">
                                        @error('tags')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Separe as tags com vírgula para facilitar a busca
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Vinculação opcional -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Vinculação (Opcional)</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="vinculo_tipo">Vincular a:</label>
                                                <select name="vinculo_tipo" id="vinculo_tipo" class="form-control">
                                                    <option value="">Não vincular</option>
                                                    <option value="obrigacao">Obrigação</option>
                                                    <option value="nota_fiscal">Nota Fiscal</option>
                                                </select>
                                            </div>

                                            <div class="form-group" id="vinculo_obrigacao" style="display: none;">
                                                <label for="obrigacao_id">Obrigação:</label>
                                                <select name="obrigacao_id" class="form-control">
                                                    <option value="">Selecione...</option>
                                                    <!-- Carregado via AJAX -->
                                                </select>
                                            </div>

                                            <div class="form-group" id="vinculo_nota_fiscal" style="display: none;">
                                                <label for="nota_fiscal_id">Nota Fiscal:</label>
                                                <select name="nota_fiscal_id" class="form-control">
                                                    <option value="">Selecione...</option>
                                                    <!-- Carregado via AJAX -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Enviar Documento
                            </button>
                            <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#vinculo_tipo').change(function() {
        var tipo = $(this).val();

        // Esconder todos os campos de vínculo
        $('#vinculo_obrigacao, #vinculo_nota_fiscal').hide();

        if (tipo === 'obrigacao') {
            $('#vinculo_obrigacao').show();
            carregarObrigacoes();
        } else if (tipo === 'nota_fiscal') {
            $('#vinculo_nota_fiscal').show();
            carregarNotasFiscais();
        }
    });

    function carregarObrigacoes() {
        var empresaId = $('select[name="empresa_id"]').val();
        if (!empresaId) return;

        $.get('/api/obrigacoes', {empresa_id: empresaId}, function(data) {
            var select = $('select[name="obrigacao_id"]');
            select.empty().append('<option value="">Selecione...</option>');

            data.forEach(function(obrigacao) {
                select.append('<option value="' + obrigacao.id + '">' + obrigacao.nome + ' - ' + obrigacao.vencimento + '</option>');
            });
        });
    }

    function carregarNotasFiscais() {
        var empresaId = $('select[name="empresa_id"]').val();
        if (!empresaId) return;

        $.get('/api/notas-fiscais', {empresa_id: empresaId}, function(data) {
            var select = $('select[name="nota_fiscal_id"]');
            select.empty().append('<option value="">Selecione...</option>');

            data.forEach(function(nota) {
                select.append('<option value="' + nota.id + '">NF ' + nota.numero_nf + ' - ' + nota.destinatario_nome + '</option>');
            });
        });
    }

    // Recarregar vinculos quando empresa mudar
    $('select[name="empresa_id"]').change(function() {
        var tipo = $('#vinculo_tipo').val();
        if (tipo === 'obrigacao') {
            carregarObrigacoes();
        } else if (tipo === 'nota_fiscal') {
            carregarNotasFiscais();
        }
    });
});
</script>
@endsection
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) alert.classList.remove('show');
                }, 10000);
            </script>
        @endif

        {{-- Lista de Documentos --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-semibold">
                Documentos Enviados
            </div>
            <div class="card-body">
                @if($documentos->count())
                    <ul class="list-group list-group-flush">
                        @foreach($documentos as $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $doc->nome_arquivo }}</strong> <br>
                                    <small class="text-muted">Protocolo: {{ $doc->protocolo }}</small>
                                </div>
                                <a href="{{ route('documentos.download', $doc) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3">
                        {{ $documentos->links() }}
                    </div>
                @else
                    <p class="text-muted">Nenhum documento enviado até o momento.</p>
                @endif
            </div>
        </div>

        {{-- Linha do Tempo --}}
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-semibold">
                Linha do Tempo dos Documentos
            </div>
            <div class="card-body">
                @forelse($documentos as $doc)
                    <div class="border-start border-4 border-primary ps-3 mb-4 position-relative">
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-calendar3"></i> {{ $doc->created_at->format('d/m/Y H:i') }}
                        </small>
                        <h5 class="mb-1 fw-bold">{{ $doc->nome_arquivo }}</h5>
                        <p class="text-secondary mb-1">Protocolo: <code>{{ $doc->protocolo }}</code></p>
                        <p class="mb-0">
                            <span class="badge bg-secondary">{{ $doc->empresa->razao_social }}</span>
                            @if($doc->obrigacao)
                                <span class="badge bg-info text-dark ms-2">{{ $doc->obrigacao->tipo }}</span>
                            @endif
                        </p>
                    </div>
                @empty
                    <p class="text-muted">Nenhum documento registrado na linha do tempo.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
