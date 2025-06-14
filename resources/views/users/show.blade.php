@extends('app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">{{ $user->name }}</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-4 mt-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Email:</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Criado em:</span>
                            <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Atualizado em:</span>
                            <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
