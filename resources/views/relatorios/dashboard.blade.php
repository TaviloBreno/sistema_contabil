@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard de Indicadores</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Cards de resumo -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $dadosGerais['empresas_ativas'] }}</h3>
                        <p>Empresas Ativas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $dadosGerais['notas_fiscais_mes'] }}</h3>
                        <p>NFes Este Mês</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $dadosGerais['obrigacoes_pendentes'] }}</h3>
                        <p>Obrigações Pendentes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $dadosGerais['documentos_upload'] }}</h3>
                        <p>Documentos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Receita por Mês ({{ date('Y') }})</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="receitaChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Impostos por Mês ({{ date('Y') }})</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="impostosChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabelas -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Obrigações Vencendo (Próximos 30 dias)</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Tipo</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                        <th>Dias</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($obrigacoesVencendo as $obrigacao)
                                    <tr>
                                        <td>{{ $obrigacao->empresa->razao_social }}</td>
                                        <td>{{ $obrigacao->tipo }}</td>
                                        <td>{{ $obrigacao->data_vencimento->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge badge-warning">{{ $obrigacao->status }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $diasRestantes = now()->diffInDays($obrigacao->data_vencimento, false);
                                            @endphp
                                            @if($diasRestantes < 0)
                                                <span class="text-danger">{{ abs($diasRestantes) }} dias em atraso</span>
                                            @else
                                                <span class="text-info">{{ $diasRestantes }} dias</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Empresas com Mais Obrigações</h3>
                    </div>
                    <div class="card-body">
                        @foreach($empresasComMaisObrigacoes as $empresa)
                        <div class="progress-group">
                            {{ $empresa->razao_social }}
                            <span class="float-right"><b>{{ $empresa->obrigacoes_count }}</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: {{ ($empresa->obrigacoes_count / 10) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Receita
const receitaCtx = document.getElementById('receitaChart').getContext('2d');
const receitaChart = new Chart(receitaCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Receita (R$)',
            data: [
                @foreach(range(1, 12) as $mes)
                    {{ $receitasPorMes->where('mes', $mes)->first()->total ?? 0 }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});

// Gráfico de Impostos
const impostosCtx = document.getElementById('impostosChart').getContext('2d');
const impostosChart = new Chart(impostosCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Impostos (R$)',
            data: [
                @foreach(range(1, 12) as $mes)
                    {{ $impostosPorMes->where('mes', $mes)->first()->total ?? 0 }},
                @endforeach
            ],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});
</script>
@endsection
