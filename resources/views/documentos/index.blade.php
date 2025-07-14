@extends('app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Documentos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Documentos</li>
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
                        <h3 class="card-title">Gestão de Documentos</h3>
                        <div class="card-tools">
                            <a href="{{ route('documentos.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Novo Documento
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-control" id="filtroCategoria">
                                    <option value="">Todas as Categorias</option>
                                    <option value="nfe" {{ request('categoria') == 'nfe' ? 'selected' : '' }}>NFe</option>
                                    <option value="contrato" {{ request('categoria') == 'contrato' ? 'selected' : '' }}>Contratos</option>
                                    <option value="certidao" {{ request('categoria') == 'certidao' ? 'selected' : '' }}>Certidões</option>
                                    <option value="licenca" {{ request('categoria') == 'licenca' ? 'selected' : '' }}>Licenças</option>
                                    <option value="comprovante" {{ request('categoria') == 'comprovante' ? 'selected' : '' }}>Comprovantes</option>
                                    <option value="relatorio" {{ request('categoria') == 'relatorio' ? 'selected' : '' }}>Relatórios</option>
                                    <option value="outros" {{ request('categoria') == 'outros' ? 'selected' : '' }}>Outros</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="filtroEmpresa">
                                    <option value="">Todas as Empresas</option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->razao_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="busca" placeholder="Buscar documentos..." value="{{ request('busca') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" id="btnFiltrar">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                        </div>

                        <!-- Lista de documentos -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Categoria</th>
                                        <th>Empresa</th>
                                        <th>Tamanho</th>
                                        <th>Data Upload</th>
                                        <th>Usuário</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentos as $documento)
                                    <tr>
                                        <td>
                                            <i class="{{ $documento->icone }}"></i>
                                            <a href="{{ route('documentos.show', $documento) }}">
                                                {{ Str::limit($documento->nome_original, 30) }}
                                            </a>
                                        </td>
                                        <td><span class="badge badge-info">{{ $documento->categoria_descricao }}</span></td>
                                        <td>{{ $documento->empresa->razao_social }}</td>
                                        <td>{{ $documento->tamanho_formatado }}</td>
                                        <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $documento->user->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('documentos.download', $documento) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('documentos.show', $documento) }}" class="btn btn-sm btn-outline-info" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('documentos.edit', $documento) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('documentos.destroy', $documento) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este documento?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-center">
                            {{ $documentos->links() }}
                        </div>

                    </div>
                </div>

                <!-- Segunda Tabela -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body table-responsive">
                        @if($documentos->count())
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Arquivo</th>
                                        <th>Empresa</th>
                                        <th>Obrigação</th>
                                        <th>Protocolo</th>
                                        <th>Data de Envio</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentos as $doc)
                                    <tr>
                                        <td>{{ $doc->id }}</td>
                                        <td>{{ $doc->nome_arquivo }}</td>
                                        <td>{{ $doc->empresa->razao_social }}</td>
                                        <td>{{ $doc->obrigacao?->tipo ?? '—' }}</td>
                                        <td><span class="badge bg-primary">{{ $doc->protocolo }}</span></td>
                                        <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('documentos.download', $doc) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-download"></i> Baixar
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $documentos->links() }}
                            </div>
                        @else
                            <p class="text-muted">Nenhum documento cadastrado.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#btnFiltrar').click(function() {
        var categoria = $('#filtroCategoria').val();
        var empresa = $('#filtroEmpresa').val();
        var busca = $('#busca').val();

        var url = '{{ route("documentos.index") }}';
        var params = [];

        if (categoria) params.push('categoria=' + categoria);
        if (empresa) params.push('empresa_id=' + empresa);
        if (busca) params.push('busca=' + busca);

        if (params.length > 0) {
            url += '?' + params.join('&');
        }

        window.location.href = url;
    });

    $('#busca').keypress(function(e) {
        if (e.which == 13) {
            $('#btnFiltrar').click();
        }
    });
});
</script>
@endsection
