@extends('app')

@section('title', 'Cadastrar Empresa')

@section('content')
<div class="card card-info card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Cadastro de Empresa</div>
    </div>

    <form class="needs-validation" method="POST" action="{{ route('empresas.store') }}" novalidate>
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <!-- Código Interno -->
                <div class="col-md-2">
                    <label for="codigo_interno" class="form-label">Código Interno</label>
                    <input type="text" name="codigo_interno" class="form-control" id="codigo_interno" required>
                </div>

                <!-- Razão Social -->
                <div class="col-md-4">
                    <label for="razao_social" class="form-label">Razão Social</label>
                    <input type="text" name="razao_social" class="form-control" id="razao_social" required>
                </div>

                <!-- CNPJ -->
                <div class="col-md-6">
                    <label for="cnpj" class="form-label">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" class="form-control" required>
                </div>

                <!-- Nome Fantasia -->
                <div class="col-md-6">
                    <label for="fantasia" class="form-label">Nome Fantasia</label>
                    <input type="text" name="fantasia" class="form-control" id="fantasia">
                </div>

                <!-- Data de Abertura -->
                <div class="col-md-3">
                    <label for="abertura" class="form-label">Abertura</label>
                    <input type="date" name="abertura" class="form-control" id="abertura">
                </div>

                <!-- Natureza Jurídica -->
                <div class="col-md-3">
                    <label for="natureza_juridica" class="form-label">Natureza Jurídica</label>
                    <input type="text" name="natureza_juridica" class="form-control" id="natureza_juridica">
                </div>

                <!-- Porte -->
                <div class="col-md-3">
                    <label for="porte" class="form-label">Porte</label>
                    <input type="text" name="porte" class="form-control" id="porte">
                </div>

                <!-- Tipo -->
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <input type="text" name="tipo" class="form-control" id="tipo">
                </div>

                <!-- Endereço -->
                <div class="col-md-4">
                    <label for="logradouro" class="form-label">Logradouro</label>
                    <input type="text" name="logradouro" class="form-control" id="logradouro">
                </div>
                <div class="col-md-2">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" name="numero" class="form-control" id="numero">
                </div>
                <div class="col-md-2">
                    <label for="complemento" class="form-label">Complemento</label>
                    <input type="text" name="complemento" class="form-control" id="complemento">
                </div>
                <div class="col-md-4">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" name="bairro" class="form-control" id="bairro">
                </div>
                <div class="col-md-4">
                    <label for="municipio" class="form-label">Município</label>
                    <input type="text" name="municipio" class="form-control" id="municipio">
                </div>
                <div class="col-md-2">
                    <label for="uf" class="form-label">UF</label>
                    <input type="text" name="uf" class="form-control" id="uf">
                </div>
                <div class="col-md-2">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" name="cep" class="form-control" id="cep">
                </div>

                <!-- Telefone / Email -->
                <div class="col-md-4">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" id="telefone">
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" id="email">
                </div>

                <!-- Regime Tributário -->
                <div class="col-md-4">
                    <label for="regime_tributario" class="form-label">Regime Tributário</label>
                    <input type="text" name="regime_tributario" class="form-control" id="regime_tributario">
                </div>

                <!-- Capital Social -->
                <div class="col-md-3">
                    <label for="capital_social" class="form-label">Capital Social</label>
                    <input type="text" name="capital_social" class="form-control" id="capital_social">
                </div>

                <!-- Situação -->
                <div class="col-md-3">
                    <label for="situacao" class="form-label">Situação</label>
                    <input type="text" name="situacao" class="form-control" id="situacao">
                </div>
                <div class="col-md-3">
                    <label for="data_situacao" class="form-label">Data da Situação</label>
                    <input type="date" name="data_situacao" class="form-control" id="data_situacao">
                </div>

                <!-- Situação Especial -->
                <div class="col-md-3">
                    <label for="situacao_especial" class="form-label">Situação Especial</label>
                    <input type="text" name="situacao_especial" class="form-control" id="situacao_especial">
                </div>
                <div class="col-md-3">
                    <label for="data_situacao_especial" class="form-label">Data Situação Especial</label>
                    <input type="date" name="data_situacao_especial" class="form-control" id="data_situacao_especial">
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

        <div class="card-footer text-end d-flex justify-content-end gap-2">
            <button class="btn btn-info" type="submit">
                <i class="bi bi-save me-1"></i> Salvar
            </button>
            <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
