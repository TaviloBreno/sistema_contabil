@extends('app')

@section('title', 'Cadastrar Usuário')

@section('content')
    <div class="card card-info card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">Cadastro de Usuário</div>
        </div>
        <!--end::Header-->

        <!--begin::Form-->
        <form class="needs-validation" method="POST" action="{{ route('usuarios.store') }}" novalidate>
            @csrf

            <!--begin::Body-->
            <div class="card-body">
                <div class="row g-3">
                    <!-- Nome -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                        <div class="invalid-feedback">Informe o nome completo.</div>
                    </div>

                    <!-- E-mail -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                        <div class="invalid-feedback">Informe um e-mail válido.</div>
                    </div>

                    <!-- Senha -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" name="password" class="form-control" id="password" required minlength="6">
                        <div class="invalid-feedback">A senha deve ter no mínimo 6 caracteres.</div>
                    </div>

                    <!-- Confirmação de Senha -->
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                        <div class="invalid-feedback">Confirme a senha corretamente.</div>
                    </div>

                    <!-- Perfil -->
                    <div class="col-md-6">
                        <label for="role" class="form-label">Perfil</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="" selected disabled>Selecione</option>
                            <option value="admin">Administrador</option>
                            <option value="gerente">Gerente</option>
                            <option value="operador">Operador</option>
                        </select>
                        <div class="invalid-feedback">Selecione o tipo de perfil.</div>
                    </div>
                </div>
            </div>
            <!--end::Body-->

            <!--begin::Footer-->
            <div class="card-footer text-end d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-info">
                    <i class="bi bi-save me-1"></i> Salvar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </a>
            </div>
            <!--end::Footer-->
        </form>
        <!--end::Form-->
    </div>
@endsection
