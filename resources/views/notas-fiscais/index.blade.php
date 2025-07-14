@extends('app')

@section('title', 'Notas Fiscais')

@section('content')
<div class="content-header bg-light py-3 mb-4 border-bottom">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-primary">Notas Fiscais</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-secondary">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary">Notas Fiscais</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filtros -->
        <div class="card shadow-sm mb-4 border-primary">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-filter mr-2"></i> Filtros</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body bg-light">
                <form method="GET" action="{{ route('notas-fiscais.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label for="empresa_id" class="font-weight-bold">Empresa</label>
                                <select name="empresa_id" id="empresa_id" class="form-control border-primary">
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
                            <div class="form-group mb-2">
                                <label for="status" class="font-weight-bold">Status</label>
                                <select name="status" id="status" class="form-control border-primary">
                                    <option value="">Todos os status</option>
                                    <option value="rascunho" {{ request('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="autorizada" {{ request('status') == 'autorizada' ? 'selected' : '' }}>Autorizada</option>
                                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    <option value="rejeitada" {{ request('status') == 'rejeitada' ? 'selected' : '' }}>Rejeitada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label for="tipo" class="font-weight-bold">Tipo</label>
                                <select name="tipo" id="tipo" class="form-control border-primary">
                                    <option value="">Todos os tipos</option>
                                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label for="data_inicio" class="font-weight-bold">Data Início</label>
                                <input type="date" name="data_inicio" id="data_inicio" class="form-control border-primary" value="{{ request('data_inicio') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label for="data_fim" class="font-weight-bold">Data Fim</label>
                                <input type="date" name="data_fim" id="data_fim" class="form-control border-primary" value="{{ request('data_fim') }}">
                            </div>
                        </div>
                        <div class="col-md-2 align-items-end">
                            <button type="submit" class="btn btn-primary btn-block shadow-sm w-100" title="Pesquisar">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Notas Fiscais -->
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-file-invoice mr-2"></i> Lista de Notas Fiscais</h3>
                <div class="card-tools ms-auto">
                    <a href="{{ route('notas-fiscais.create') }}" class="btn btn-success btn-sm shadow-sm">
                        <i class="fas fa-plus"></i> Nova Nota Fiscal
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0 bg-light">
                <table class="table table-hover table-bordered text-nowrap mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Número</th>
                            <th>Série</th>
                            <th>Empresa</th>
                            <th>Destinatário</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Data Emissão</th>
                            <th>Valor Total</th>
                            <th class="text-center">Ações</th>
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
                                <td class="text-center">
                                    <div class="btn-group" role="group" style="gap: 0.4rem;">
                                        <a href="{{ route('notas-fiscais.show', $nota) }}" class="btn btn-info btn-sm me-1" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($nota->status === 'rascunho')
                                            <a href="{{ route('notas-fiscais.edit', $nota) }}" class="btn btn-warning btn-sm me-1" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('notas-fiscais.destroy', $nota) }}" method="POST" class="d-inline me-1" onsubmit="return confirm('Tem certeza que deseja excluir esta nota fiscal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($nota->status === 'autorizada')
                                            <a href="{{ route('notas-fiscais.xml', $nota) }}" class="btn btn-secondary btn-sm me-1" title="Download XML">
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
                                <td colspan="9" class="text-center text-muted">Nenhuma nota fiscal encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notasFiscais->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $notasFiscais->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
