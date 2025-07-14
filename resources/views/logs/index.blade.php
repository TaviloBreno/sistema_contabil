@extends('app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Logs de Atividade</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Logs</li>
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
                        <h3 class="card-title">Histórico de Atividades</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#clearLogsModal">
                                <i class="fas fa-trash"></i> Limpar Logs
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-control" id="filtroTipo">
                                    <option value="">Todos os Tipos</option>
                                    <option value="created">Criados</option>
                                    <option value="updated">Atualizados</option>
                                    <option value="deleted">Excluídos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="filtroModelo">
                                    <option value="">Todos os Modelos</option>
                                    <option value="App\Models\NotaFiscal">Notas Fiscais</option>
                                    <option value="App\Models\Empresa">Empresas</option>
                                    <option value="App\Models\DocumentoUpload">Documentos</option>
                                    <option value="App\Models\Obrigacao">Obrigações</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" id="dataInicio" placeholder="Data Início">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" id="dataFim" placeholder="Data Fim">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" id="btnFiltrar">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                        </div>

                        <!-- Lista de atividades -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Usuário</th>
                                        <th>Ação</th>
                                        <th>Modelo</th>
                                        <th>Descrição</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            @if($activity->causer)
                                                {{ $activity->causer->name }}
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->description == 'created')
                                                <span class="badge badge-success">Criado</span>
                                            @elseif($activity->description == 'updated')
                                                <span class="badge badge-warning">Atualizado</span>
                                            @elseif($activity->description == 'deleted')
                                                <span class="badge badge-danger">Excluído</span>
                                            @else
                                                <span class="badge badge-info">{{ $activity->description }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->subject_type)
                                                {{ class_basename($activity->subject_type) }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->subject_type == 'App\Models\NotaFiscal' && $activity->subject)
                                                NF {{ $activity->subject->numero_nf ?? 'N/A' }}
                                            @elseif($activity->subject_type == 'App\Models\Empresa' && $activity->subject)
                                                {{ $activity->subject->razao_social ?? 'N/A' }}
                                            @elseif($activity->subject_type == 'App\Models\DocumentoUpload' && $activity->subject)
                                                {{ $activity->subject->nome_original ?? 'N/A' }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('logs.show', $activity) }}" class="btn btn-sm btn-outline-info" title="Detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-center">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para limpar logs -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Limpar Logs</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('logs.clear') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Remover logs mais antigos que:</p>
                    <div class="form-group">
                        <label for="dias">Dias:</label>
                        <input type="number" name="dias" class="form-control" min="1" max="365" value="30" required>
                    </div>
                    <p class="text-warning"><small>Esta ação não pode ser desfeita!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Limpar Logs</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#btnFiltrar').click(function() {
        var tipo = $('#filtroTipo').val();
        var modelo = $('#filtroModelo').val();
        var dataInicio = $('#dataInicio').val();
        var dataFim = $('#dataFim').val();

        var url = '{{ route("logs.index") }}';
        var params = [];

        if (tipo) params.push('description=' + tipo);
        if (modelo) params.push('subject_type=' + modelo);
        if (dataInicio) params.push('data_inicio=' + dataInicio);
        if (dataFim) params.push('data_fim=' + dataFim);

        if (params.length > 0) {
            url += '?' + params.join('&');
        }

        window.location.href = url;
    });
});
</script>
@endsection
