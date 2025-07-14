@extends('app')

@section('title', 'Configurações do Sistema')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white fw-semibold">
        <i class="fas fa-cogs"></i> Configurações do Sistema
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('configuracoes.store') }}">
            @csrf

            @forelse($configuracoes as $grupo => $configs)
                <h5 class="mt-4 mb-3 border-bottom pb-1 text-muted">{{ ucwords(str_replace('_', ' ', $grupo)) }}</h5>

                @foreach($configs as $config)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ ucwords(str_replace('_', ' ', $config->chave)) }}
                        </label>

                        @if($config->tipo === 'boolean')
                            <div class="form-check form-switch">
                                <input type="hidden" name="configuracoes[{{ $config->id }}]" value="0">
                                <input type="checkbox"
                                       class="form-check-input"
                                       name="configuracoes[{{ $config->id }}]"
                                       value="1"
                                       {{ $config->valor ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $config->descricao ?? '' }}</label>
                            </div>
                        @elseif($config->tipo === 'text' || $config->tipo === null)
                            <input type="text"
                                   name="configuracoes[{{ $config->id }}]"
                                   class="form-control"
                                   value="{{ old("configuracoes.{$config->id}", $config->valor) }}"
                                   placeholder="Informe {{ strtolower(str_replace('_', ' ', $config->chave)) }}">
                        @elseif($config->tipo === 'textarea')
                            <textarea name="configuracoes[{{ $config->id }}]"
                                      class="form-control"
                                      rows="3">{{ old("configuracoes.{$config->id}", $config->valor) }}</textarea>
                        @endif

                        @if($config->descricao)
                            <small class="text-muted">{{ $config->descricao }}</small>
                        @endif
                    </div>
                @endforeach
            @empty
                <p class="text-muted">Nenhuma configuração encontrada no sistema.</p>
            @endforelse

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar Configurações
            </button>
        </form>
    </div>
</div>
@endsection
