@extends('app')

@section('title', 'Configurações do Sistema')

@section('content')
<div class="card">
    <div class="card-header bg-dark text-white fw-semibold">
        Configurações do Sistema
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('configuracoes.store') }}">
            @csrf
            @foreach($configuracoes as $config)
            <div class="mb-3">
                <label class="form-label">{{ ucfirst(str_replace('_', ' ', $config->chave)) }}</label>
                <input type="text" name="configuracoes[{{ $config->id }}]" class="form-control" value="{{ $config->valor }}">
            </div>
            @endforeach

            <button class="btn btn-primary">Salvar Configurações</button>
        </form>
    </div>
</div>
@endsection
