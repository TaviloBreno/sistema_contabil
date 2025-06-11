@extends('app')

@section('title', 'Empresas - Linha do Tempo')

@section('content')
<div class="card mb-5">
    <div class="card-header bg-dark text-white fw-semibold d-flex justify-content-between align-items-center">
        <span>Linha do Tempo das Obrigações</span>
        <a href="{{ route('obrigacoes.create') }}" class="btn btn-sm btn-light text-dark">
            <i class="bi bi-plus-circle"></i> Nova Obrigação
        </a>
    </div>
    <div class="card-body">
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
        <div class="d-flex justify-content-center mt-4">
            {{ $obrigacoes->links() }}
        </div>
        @else
        <p class="text-muted">Nenhuma obrigação registrada.</p>
        @endif
    </div>
</div>
@endsection
