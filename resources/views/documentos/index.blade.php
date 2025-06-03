@extends('app')

@section('title', 'Documentos Cadastrados')

@section('content')
    <div class="container">
        <h1 class="mb-4">Documentos Cadastrados</h1>

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

        {{-- Tabela --}}
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                @if($documentos->count())
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Arquivo</th>
                            <th>Empresa</th>
                            <th>Obrigação</th>
                            <th>Protocolo</th>
                            <th>Data de Envio</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($documentos as $doc)
                            <tr>
                                <td>{{ $doc->id }}</td>
                                <td>{{ $doc->nome_arquivo }}</td>
                                <td>{{ $doc->empresa->razao_social }}</td>
                                <td>{{ $doc->obrigacao?->tipo ?? '—' }}</td>
                                <td><span class="badge bg-primary">{{ $doc->protocolo }}</span></td>
                                <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('documentos.download', $doc) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-download"></i> Baixar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $documentos->links() }}
                    </div>
                @else
                    <p class="text-muted">Nenhum documento cadastrado.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
