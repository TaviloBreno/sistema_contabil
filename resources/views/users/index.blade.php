@extends('app')

@section('title', 'Usuários')

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
            <h3 class="card-title mb-0">Usuários Cadastrados</h3>

            <div class="ms-auto">
                <a href="{{ route('usuarios.create') }}" class="btn btn-info">
                    <i class="fas fa-plus-circle me-1"></i> Adicionar Usuário
                </a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Data de Criação</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            @if($usuario->role === 'admin')
                                <span class="badge bg-danger">Administrador</span>
                            @elseif($usuario->role === 'gerente')
                                <span class="badge bg-warning text-dark">Gerente</span>
                            @else
                                <span class="badge bg-secondary">Operador</span>
                            @endif
                        </td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-sm btn-info" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este usuário?')">
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
                        <td colspan="6" class="text-center">Nenhum usuário cadastrado.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="mt-3">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
@endsection
