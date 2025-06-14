@extends('app')

@section('title', 'Visualizar Empresa')

@section('content')
<div class="container my-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex align-items-center" style="background: linear-gradient(90deg, #0056b3 0%, #007bff 100%);">
            <i class="bi bi-building me-2 fs-3"></i>
            <h3 class="mb-0 fw-bold">{{ $empresa->razao_social }}</h3>
        </div>
        <div class="card-body px-5 py-4">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-secondary fw-semibold">Código Interno:</dt>
                <dd class="col-sm-8">{{ $empresa->codigo_interno }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Razão Social:</dt>
                <dd class="col-sm-8">{{ $empresa->razao_social }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">CNPJ:</dt>
                <dd class="col-sm-8">{{ $empresa->cnpj }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Nome Fantasia:</dt>
                <dd class="col-sm-8">{{ $empresa->fantasia }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Abertura:</dt>
                <dd class="col-sm-8">{{ $empresa->abertura }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Natureza Jurídica:</dt>
                <dd class="col-sm-8">{{ $empresa->natureza_juridica }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Porte:</dt>
                <dd class="col-sm-8">{{ $empresa->porte }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Tipo:</dt>
                <dd class="col-sm-8">{{ $empresa->tipo }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Logradouro:</dt>
                <dd class="col-sm-8">{{ $empresa->logradouro }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Número:</dt>
                <dd class="col-sm-8">{{ $empresa->numero }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Complemento:</dt>
                <dd class="col-sm-8">{{ $empresa->complemento }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Bairro:</dt>
                <dd class="col-sm-8">{{ $empresa->bairro }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Município:</dt>
                <dd class="col-sm-8">{{ $empresa->municipio }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">UF:</dt>
                <dd class="col-sm-8">{{ $empresa->uf }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">CEP:</dt>
                <dd class="col-sm-8">{{ $empresa->cep }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Telefone:</dt>
                <dd class="col-sm-8">{{ $empresa->telefone }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Email:</dt>
                <dd class="col-sm-8">{{ $empresa->email }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Regime Tributário:</dt>
                <dd class="col-sm-8">{{ $empresa->regime_tributario }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Capital Social:</dt>
                <dd class="col-sm-8">{{ $empresa->capital_social }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Situação:</dt>
                <dd class="col-sm-8">{{ $empresa->situacao }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Data da Situação:</dt>
                <dd class="col-sm-8">{{ $empresa->data_situacao }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Situação Especial:</dt>
                <dd class="col-sm-8">{{ $empresa->situacao_especial }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Data da Situação Especial:</dt>
                <dd class="col-sm-8">{{ $empresa->data_situacao_especial }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Sócios:</dt>
                <dd class="col-sm-8">{{ $empresa->socios }}</dd>

                <dt class="col-sm-4 text-secondary fw-semibold">Matriz:</dt>
                <dd class="col-sm-8">
                    @if($empresa->matriz)
                    {{ $empresa->matriz->razao_social }}
                    @else
                    <span class="text-muted">Não possui matriz</span>
                    @endif
                </dd>

            </dl>
        </div>
    </div>
</div>
<!-- Adicione este bloco se quiser ícones do Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .card-header.bg-gradient-primary {
        background: linear-gradient(90deg, #0056b3 0%, #007bff 100%) !important;
    }

    .card {
        border-radius: 1.5rem !important;
    }

    dt {
        margin-bottom: 0.5rem;
    }

    dd {
        margin-bottom: 0.5rem;
    }
</style>
@endsection
