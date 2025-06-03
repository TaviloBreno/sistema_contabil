@extends('app')

@section('title', 'Relatórios e Indicadores')

@section('content')
    <div class="container">
        <h1>Relatórios de Obrigações</h1>

        <form method="GET" action="{{ route('relatorios.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label>Mês</label>
                <input type="month" name="mes" class="form-control" value="{{ request('mes') }}">
            </div>
            <div class="col-md-3">
                <label>Tipo de Obrigação</label>
                <input type="text" name="tipo" class="form-control" value="{{ request('tipo') }}">
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="em andamento" {{ request('status') == 'em andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary">Filtrar</button>
            </div>
        </form>

        <a href="{{ route('relatorios.exportarPdf', request()->query()) }}" class="btn btn-outline-danger mb-4">Exportar PDF</a>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Início</th>
                        <th>Vencimento</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($obrigacoes as $obrigacao)
                        <tr>
                            <td>{{ $obrigacao->empresa->razao_social }}</td>
                            <td>{{ $obrigacao->tipo }}</td>
                            <td>{{ ucfirst($obrigacao->status) }}</td>
                            <td>{{ \Carbon\Carbon::parse($obrigacao->data_inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($obrigacao->data_vencimento)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
