@extends('app')

@section('title', 'Notas Fiscais')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Notas Fiscais</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notas Fiscais</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filtros -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtros</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('notas-fiscais.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="empresa_id">Empresa</label>
                                <select name="empresa_id" id="empresa_id" class="form-control">
                                    <option value="">Todas as empresas</option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->razao_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Todos os status</option>
                                    <option value="rascunho" {{ request('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="autorizada" {{ request('status') == 'autorizada' ? 'selected' : '' }}>Autorizada</option>
                                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    <option value="rejeitada" {{ request('status') == 'rejeitada' ? 'selected' : '' }}>Rejeitada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value="">Todos os tipos</option>
                                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_inicio">Data Início</label>
                                <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_fim">Data Fim</label>
                                <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ request('data_fim') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Notas Fiscais -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Notas Fiscais</h3>
                <div class="card-tools">
                    <a href="{{ route('notas-fiscais.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nova Nota Fiscal
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Série</th>
                            <th>Empresa</th>
                            <th>Destinatário</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Data Emissão</th>
                            <th>Valor Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notasFiscais as $nota)
                            <tr>
                                <td>{{ $nota->numero_nf }}</td>
                                <td>{{ $nota->serie }}</td>
                                <td>{{ $nota->empresa->razao_social }}</td>
                                <td>{{ $nota->destinatario_nome }}</td>
                                <td>{!! $nota->tipo_badge !!}</td>
                                <td>{!! $nota->status_badge !!}</td>
                                <td>{{ $nota->data_emissao->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($nota->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('notas-fiscais.show', $nota) }}" class="btn btn-info btn-sm" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($nota->status === 'rascunho')
                                            <a href="{{ route('notas-fiscais.edit', $nota) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('notas-fiscais.destroy', $nota) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta nota fiscal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($nota->status === 'autorizada')
                                            <a href="{{ route('notas-fiscais.xml', $nota) }}" class="btn btn-secondary btn-sm" title="Download XML">
                                                <i class="fas fa-file-code"></i>
                                            </a>
                                            <a href="{{ route('notas-fiscais.danfe', $nota) }}" class="btn btn-primary btn-sm" title="DANFE" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Nenhuma nota fiscal encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notasFiscais->hasPages())
                <div class="card-footer">
                    {{ $notasFiscais->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
