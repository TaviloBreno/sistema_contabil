@extends('app')

@section('title', 'Empresas - Linha do Tempo')

@section('content')
<div class="card mb-5">
    <div class="card-header bg-dark text-white fw-semibold d-flex justify-content-between align-items-center">
        <span>Linha do Tempo das Obrigações</span>
        <div class="ms-auto">
            <a href="{{ route('obrigacoes.create') }}" class="btn btn-sm btn-light text-dark">
                <i class="bi bi-plus-circle"></i> Nova Obrigação
            </a>
        </div>
    </div>

    <div class="card-body">

        {{-- Filtros --}}
        <form method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="tipo" class="form-control" placeholder="Tipo da obrigação" value="{{ request('tipo') }}">
                </div>
                <div class="col-md-2">
                    <select name="frequencia" class="form-select">
                        <option value="">Frequência</option>
                        @foreach(['mensal', 'trimestral', 'anual'] as $f)
                        <option value="{{ $f }}" @selected(request('frequencia')==$f)>
                            {{ ucfirst($f) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Status</option>
                        @foreach(['pendente', 'em andamento', 'concluida'] as $s)
                        <option value="{{ $s }}" @selected(request('status')==$s)>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="empresa_id" class="form-select">
                        <option value="">Empresa</option>
                        @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" @selected(request('empresa_id')==$empresa->id)>
                            {{ $empresa->razao_social }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        {{-- Linha do tempo --}}
        @if($obrigacoes->count())
        <div class="row">
            @foreach($obrigacoes->chunk(5) as $chunk)
            <div class="col-md-6">
                @foreach($chunk as $obrigacao)
                <div class="timeline-item mb-4 border-start border-4 ps-3 border-primary position-relative">
                    <div class="small text-muted mb-1">
                        <i class="bi bi-calendar3"></i>
                        {{ $obrigacao->created_at->format('d/m/Y H:i') }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $obrigacao->tipo ?? 'Tipo não informado' }}</h5>
                    <p class="mb-0 text-secondary">
                        Frequência: <strong>{{ ucfirst($obrigacao->frequencia) }}</strong><br>
                        Vencimento: <strong>{{ \Carbon\Carbon::parse($obrigacao->data_vencimento)->format('d/m/Y') }}</strong><br>
                        Status: <span class="badge bg-info text-dark">{{ ucfirst($obrigacao->status) }}</span>
                    </p>
                    @if($obrigacao->empresa)
                    <span class="badge bg-secondary mt-2">{{ $obrigacao->empresa->razao_social }}</span>
                    @endif
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $obrigacoes->links() }}
        </div>
        @else
        <p class="text-muted">Nenhuma obrigação registrada.</p>
        @endif
    </div>
</div>
@endsection
