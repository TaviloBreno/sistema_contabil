@extends('app')

@section('title', 'Editar Usuário')

@section('content')
    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <!--begin::Header-->
        <div class="card-header bg-primary text-white rounded-top-4">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="bi bi-person-lines-fill me-2"></i> Edição de Usuário
            </h5>
        </div>
        <!--end::Header-->

        <!--begin::Form-->
        <form class="needs-validation" method="POST" action="{{ route('usuarios.update', $usuario->id) }}" novalidate autocomplete="off">
            @csrf
            @method('PUT')

            <!--begin::Body-->
            <div class="card-body py-4">
                <div class="row g-4">
                    <!-- Nome -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nome</label>
                        <input type="text" name="name" class="form-control form-control-lg" id="name" value="{{ old('name', $usuario->name) }}" required>
                        <div class="invalid-feedback">Informe o nome completo.</div>
                    </div>

                    <!-- E-mail -->
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control form-control-lg" id="email" value="{{ old('email', $usuario->email) }}" required>
                        <div class="invalid-feedback">Informe um e-mail válido.</div>
                    </div>

                    <!-- Senha -->
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold">Senha <span class="text-muted">(deixe em branco para não alterar)</span></label>
                        <input type="password" name="password" class="form-control form-control-lg" id="password" minlength="6" autocomplete="new-password">
                        <div class="invalid-feedback">A senha deve ter no mínimo 6 caracteres.</div>
                    </div>

                    <!-- Confirmação de Senha -->
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg" id="password_confirmation" autocomplete="new-password">
                        <div class="invalid-feedback">Confirme a senha corretamente.</div>
                    </div>

                    <!-- Perfil -->
                    <div class="col-md-6">
                        <label for="role" class="form-label fw-semibold">Perfil</label>
                        <select name="role" id="role" class="form-select form-select-lg" required>
                            <option value="" disabled {{ old('role', $usuario->role) ? '' : 'selected' }}>Selecione</option>
                            <option value="admin" {{ old('role', $usuario->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="gerente" {{ old('role', $usuario->role) === 'gerente' ? 'selected' : '' }}>Gerente</option>
                            <option value="operador" {{ old('role', $usuario->role) === 'operador' ? 'selected' : '' }}>Operador</option>
                        </select>
                        <div class="invalid-feedback">Selecione o tipo de perfil.</div>
                    </div>
                </div>
            </div>
            <!--end::Body-->
            <div class="card-footer bg-light d-flex justify-content-end rounded-bottom-4">
                <button type="submit" class="btn btn-success btn-lg px-4">
                    <i class="bi bi-save me-2"></i> Salvar
                </button>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <style>
        .card {
            max-width: 800px;
            margin: 0 auto;
        }
        .form-label {
            margin-bottom: 0.3rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
        }
        .invalid-feedback {
            font-size: 0.95em;
        }
    </style>
@endsection
