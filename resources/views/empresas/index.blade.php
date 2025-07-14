@extends('app')

@section('title', 'Empresas')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-outline card-info">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Empresas Cadastradas</h3>

            <div class="ms-auto">
                <a href="{{ route('empresas.create') }}" class="btn btn-info">
                    <i class="fas fa-plus-circle me-1"></i> Adicionar Empresa
                </a>
            </div>
        </div>


        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Razão Social</th>
                    <th>CNPJ</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Matriz</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($empresas as $empresa)
                    <tr>
                        <td>{{ $empresa->id }}</td>
                        <td>{{ $empresa->razao_social }}</td>
                        <td>{{ $empresa->cnpj }}</td>
                        <td>{{ $empresa->email ?? '-' }}</td>
                        <td>{{ $empresa->telefone ?? '-' }}</td>
                        <td>{{ $empresa->matriz->razao_social ?? '---' }}</td>
                        <td class="text-end">
                            <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-sm btn-info" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir esta empresa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhuma empresa cadastrada.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="mt-3">
                {{ $empresas->links() }}
            </div>
        </div>
    </div>
@endsection
