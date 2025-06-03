@extends('app')

@section('title', 'Documentos')

@section('content')
    <div class="container">
        <h1 class="mb-4">Upload de Documentos</h1>

        {{-- Formulário de Envio --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="empresa_id" class="form-label fw-semibold">Empresa <span class="text-danger">*</span></label>
                        <select name="empresa_id" id="empresa_id" class="form-select" required>
                            <option value="" disabled selected>Selecione a empresa</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->razao_social }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="obrigacao_id" class="form-label">Obrigação</label>
                        <select name="obrigacao_id" id="obrigacao_id" class="form-select">
                            <option value="">Sem obrigação</option>
                            @foreach($obrigacoes as $obrigacao)
                                <option value="{{ $obrigacao->id }}">{{ $obrigacao->tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="arquivo" class="form-label fw-semibold">Arquivo <span class="text-danger">*</span></label>
                        <input type="file" name="arquivo" id="arquivo" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-upload"></i> Enviar Documento
                    </button>
                </form>
            </div>
        </div>

        {{-- Alerta de sucesso --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) alert.classList.remove('show');
                }, 10000);
            </script>
        @endif

        {{-- Lista de Documentos --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-semibold">
                Documentos Enviados
            </div>
            <div class="card-body">
                @if($documentos->count())
                    <ul class="list-group list-group-flush">
                        @foreach($documentos as $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $doc->nome_arquivo }}</strong> <br>
                                    <small class="text-muted">Protocolo: {{ $doc->protocolo }}</small>
                                </div>
                                <a href="{{ route('documentos.download', $doc) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3">
                        {{ $documentos->links() }}
                    </div>
                @else
                    <p class="text-muted">Nenhum documento enviado até o momento.</p>
                @endif
            </div>
        </div>

        {{-- Linha do Tempo --}}
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-semibold">
                Linha do Tempo dos Documentos
            </div>
            <div class="card-body">
                @forelse($documentos as $doc)
                    <div class="border-start border-4 border-primary ps-3 mb-4 position-relative">
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-calendar3"></i> {{ $doc->created_at->format('d/m/Y H:i') }}
                        </small>
                        <h5 class="mb-1 fw-bold">{{ $doc->nome_arquivo }}</h5>
                        <p class="text-secondary mb-1">Protocolo: <code>{{ $doc->protocolo }}</code></p>
                        <p class="mb-0">
                            <span class="badge bg-secondary">{{ $doc->empresa->razao_social }}</span>
                            @if($doc->obrigacao)
                                <span class="badge bg-info text-dark ms-2">{{ $doc->obrigacao->tipo }}</span>
                            @endif
                        </p>
                    </div>
                @empty
                    <p class="text-muted">Nenhum documento registrado na linha do tempo.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
