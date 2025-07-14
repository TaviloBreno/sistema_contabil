@extends('app')

@section('title', 'Detalhes da Obrigação')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-primary">
        <i class="bi bi-file-earmark-text"></i> Detalhes da Obrigação
    </h1>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white fw-semibold fs-5">
            {{ $obrigacao->tipo ?? 'Tipo não informado' }}
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Frequência:</dt>
                <dd class="col-sm-9">{{ ucfirst($obrigacao->frequencia) }}</dd>

                <dt class="col-sm-3">Data de Início:</dt>
                <dd class="col-sm-9">
                    {{ optional($obrigacao->data_inicio)->format('d/m/Y') ?? 'Não definida' }}
                </dd>

                <dt class="col-sm-3">Data de Vencimento:</dt>
                <dd class="col-sm-9">
                    {{ optional($obrigacao->data_vencimento)->format('d/m/Y') ?? 'Não definida' }}
                </dd>

                <dt class="col-sm-3">Data de Conclusão:</dt>
                <dd class="col-sm-9">
                    {{ optional($obrigacao->data_conclusao)->format('d/m/Y') ?? 'Não concluída' }}
                </dd>

                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">
                    <span class="badge px-3 py-2 fs-6
                        @if($obrigacao->status === 'pendente') bg-warning text-dark
                        @elseif($obrigacao->status === 'em andamento') bg-info text-dark
                        @elseif($obrigacao->status === 'concluida') bg-success
                        @else bg-secondary @endif">
                        {{ ucfirst($obrigacao->status) }}
                    </span>
                </dd>

                <dt class="col-sm-3">Empresa:</dt>
                <dd class="col-sm-9">
                    {{ $obrigacao->empresa?->razao_social ?? 'Não vinculada' }}
                </dd>

                <dt class="col-sm-3">Observações:</dt>
                <dd class="col-sm-9">
                    {{ $obrigacao->observacoes ?? 'Nenhuma observação' }}
                </dd>
            </dl>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('obrigacoes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <a href="{{ route('obrigacoes.edit', $obrigacao) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <form action="{{ route('obrigacoes.destroy', $obrigacao) }}" method="POST" class="d-inline"
            onsubmit="return confirm('Deseja realmente excluir esta obrigação?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Excluir
            </button>
        </form>
    </div>
</div>
@endsection
