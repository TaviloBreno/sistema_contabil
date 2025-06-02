@extends('app')

@section('title', 'Cadastrar Empresa')

@section('content')
    <div class="card card-info card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">Cadastro de Empresa</div>
        </div>
        <!--end::Header-->

        <!--begin::Form-->
        <form class="needs-validation" method="POST" action="{{ route('empresas.store') }}" novalidate>
            @csrf

            <!--begin::Body-->
            <div class="card-body">
                <div class="row g-3">
                    <!-- Código Interno -->
                    <div class="col-md-2">
                        <label for="codigo_interno" class="form-label">Código Interno</label>
                        <input type="text" name="codigo_interno" class="form-control" id="codigo_interno" required>
                        <div class="invalid-feedback">Informe o código interno.</div>
                    </div>

                    <!-- Razão Social -->
                    <div class="col-md-4">
                        <label for="razao_social" class="form-label">Razão Social</label>
                        <input type="text" name="razao_social" class="form-control" id="razao_social" required>
                        <div class="invalid-feedback">Informe a razão social.</div>
                    </div>

                    <!-- CNPJ -->
                    <div class="col-md-6">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control" required>
                        <div class="invalid-feedback">Informe o CNPJ.</div>
                    </div>

                    <!-- Telefone -->
                    <div class="col-md-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control" id="telefone">
                    </div>

                    <!-- Email -->
                    <div class="col-md-4">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" id="email">
                    </div>

                    <!-- Regime Tributário -->
                    <div class="col-md-5">
                        <label for="regime_tributario" class="form-label">Regime Tributário</label>
                        <input type="text" name="regime_tributario" class="form-control" id="regime_tributario">
                    </div>

                    <!-- Sócios -->
                    <div class="col-md-6">
                        <label for="socios" class="form-label">Sócios (separados por vírgula)</label>
                        <input type="text" name="socios" class="form-control" id="socios">
                    </div>

                    <!-- Matriz -->
                    <div class="col-md-6">
                        <label for="matriz_id" class="form-label">Matriz (opcional)</label>
                        <select name="matriz_id" id="matriz_id" class="form-select">
                            <option value="">Selecionar</option>
                            @foreach($matrizes as $matriz)
                                <option value="{{ $matriz->id }}">{{ $matriz->razao_social }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <!--end::Body-->

            <!--begin::Footer-->
            <div class="card-footer text-end d-flex justify-content-end gap-2">
                <button class="btn btn-info" type="submit">
                    <i class="bi bi-save me-1"></i> Salvar
                </button>
                <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </a>
            </div>
            <!--end::Footer-->
        </form>
        <!--end::Form-->
    </div>

@endsection
