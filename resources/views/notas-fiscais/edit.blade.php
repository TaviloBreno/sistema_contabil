@extends('app')

@section('title', 'Editar Nota Fiscal')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Nota Fiscal {{ $notaFiscal->numero_nf }}/{{ $notaFiscal->serie }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('notas-fiscais.index') }}">Notas Fiscais</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('notas-fiscais.show', $notaFiscal) }}">{{ $notaFiscal->numero_nf }}</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('notas-fiscais.update', $notaFiscal) }}" method="POST" id="form-nota-fiscal">
            @csrf
            @method('PUT')

            <!-- Dados Gerais -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dados Gerais</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="empresa_id">Empresa *</label>
                                <select name="empresa_id" id="empresa_id" class="form-control @error('empresa_id') is-invalid @enderror" required>
                                    <option value="">Selecione uma empresa</option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ (old('empresa_id') ?? $notaFiscal->empresa_id) == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->razao_social }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('empresa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo">Tipo *</label>
                                <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                    <option value="">Selecione</option>
                                    <option value="saida" {{ (old('tipo') ?? $notaFiscal->tipo) == 'saida' ? 'selected' : '' }}>Saída</option>
                                    <option value="entrada" {{ (old('tipo') ?? $notaFiscal->tipo) == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="serie">Série</label>
                                <input type="text" name="serie" id="serie" class="form-control" value="{{ old('serie') ?? $notaFiscal->serie }}" maxlength="5" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <select name="modelo" id="modelo" class="form-control">
                                    <option value="55" {{ (old('modelo') ?? $notaFiscal->modelo) == '55' ? 'selected' : '' }}>55 - NFe</option>
                                    <option value="65" {{ (old('modelo') ?? $notaFiscal->modelo) == '65' ? 'selected' : '' }}>65 - NFCe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_emissao">Data Emissão *</label>
                                <input type="date" name="data_emissao" id="data_emissao" class="form-control @error('data_emissao') is-invalid @enderror" value="{{ old('data_emissao') ?? $notaFiscal->data_emissao->format('Y-m-d') }}" required>
                                @error('data_emissao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados do Destinatário -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dados do Destinatário</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="destinatario_nome">Nome/Razão Social *</label>
                                <input type="text" name="destinatario_nome" id="destinatario_nome" class="form-control @error('destinatario_nome') is-invalid @enderror" value="{{ old('destinatario_nome') ?? $notaFiscal->destinatario_nome }}" required>
                                @error('destinatario_nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="destinatario_documento">CPF/CNPJ *</label>
                                <input type="text" name="destinatario_documento" id="destinatario_documento" class="form-control @error('destinatario_documento') is-invalid @enderror" value="{{ old('destinatario_documento') ?? $notaFiscal->destinatario_documento }}" required>
                                @error('destinatario_documento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="destinatario_endereco">Endereço</label>
                                <input type="text" name="destinatario_endereco" id="destinatario_endereco" class="form-control" value="{{ old('destinatario_endereco') ?? $notaFiscal->destinatario_endereco }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="destinatario_cidade">Cidade</label>
                                <input type="text" name="destinatario_cidade" id="destinatario_cidade" class="form-control" value="{{ old('destinatario_cidade') ?? $notaFiscal->destinatario_cidade }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="destinatario_uf">UF</label>
                                <select name="destinatario_uf" id="destinatario_uf" class="form-control">
                                    <option value="">UF</option>
                                    @php
                                        $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                                        $selectedUf = old('destinatario_uf') ?? $notaFiscal->destinatario_uf;
                                    @endphp
                                    @foreach($ufs as $uf)
                                        <option value="{{ $uf }}" {{ $selectedUf == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="destinatario_cep">CEP</label>
                                <input type="text" name="destinatario_cep" id="destinatario_cep" class="form-control" value="{{ old('destinatario_cep') ?? $notaFiscal->destinatario_cep }}" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="destinatario_telefone">Telefone</label>
                                <input type="text" name="destinatario_telefone" id="destinatario_telefone" class="form-control" value="{{ old('destinatario_telefone') ?? $notaFiscal->destinatario_telefone }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="destinatario_email">E-mail</label>
                                <input type="email" name="destinatario_email" id="destinatario_email" class="form-control" value="{{ old('destinatario_email') ?? $notaFiscal->destinatario_email }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itens da Nota Fiscal -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Itens da Nota Fiscal</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="adicionar-item">
                            <i class="fas fa-plus"></i> Adicionar Item
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabela-itens">
                            <thead>
                                <tr>
                                    <th width="10%">Código</th>
                                    <th width="25%">Descrição</th>
                                    <th width="8%">CFOP</th>
                                    <th width="8%">Unid.</th>
                                    <th width="10%">Qtd.</th>
                                    <th width="12%">Vlr. Unit.</th>
                                    <th width="12%">Vlr. Total</th>
                                    <th width="8%">ICMS %</th>
                                    <th width="7%">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="itens-container">
                                @foreach($notaFiscal->itens as $index => $item)
                                <tr class="item-row">
                                    <td>
                                        <input type="text" name="itens[{{ $index }}][codigo_produto]" class="form-control form-control-sm" value="{{ $item->codigo_produto }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="itens[{{ $index }}][descricao]" class="form-control form-control-sm" value="{{ $item->descricao }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="itens[{{ $index }}][cfop]" class="form-control form-control-sm" value="{{ $item->cfop }}" maxlength="4" required>
                                    </td>
                                    <td>
                                        <input type="text" name="itens[{{ $index }}][unidade]" class="form-control form-control-sm" value="{{ $item->unidade }}" maxlength="10">
                                    </td>
                                    <td>
                                        <input type="number" name="itens[{{ $index }}][quantidade]" class="form-control form-control-sm quantidade" step="0.0001" min="0.0001" value="{{ $item->quantidade }}" required>
                                    </td>
                                    <td>
                                        <input type="number" name="itens[{{ $index }}][valor_unitario]" class="form-control form-control-sm valor-unitario" step="0.01" min="0.01" value="{{ $item->valor_unitario }}" required>
                                    </td>
                                    <td>
                                        <input type="number" name="itens[{{ $index }}][valor_total]" class="form-control form-control-sm valor-total" step="0.01" value="{{ $item->valor_total }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="itens[{{ $index }}][icms_aliquota]" class="form-control form-control-sm" step="0.01" min="0" max="100" value="{{ $item->icms_aliquota }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remover-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Totais e Informações Adicionais -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Totais e Informações Adicionais</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="observacoes">Observações</label>
                                <textarea name="observacoes" id="observacoes" class="form-control" rows="4">{{ old('observacoes') ?? $notaFiscal->observacoes }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="valor_frete">Valor do Frete</label>
                                        <input type="number" name="valor_frete" id="valor_frete" class="form-control" step="0.01" min="0" value="{{ old('valor_frete') ?? $notaFiscal->valor_frete }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="valor_seguro">Valor do Seguro</label>
                                        <input type="number" name="valor_seguro" id="valor_seguro" class="form-control" step="0.01" min="0" value="{{ old('valor_seguro') ?? $notaFiscal->valor_seguro }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="valor_desconto">Valor do Desconto</label>
                                        <input type="number" name="valor_desconto" id="valor_desconto" class="form-control" step="0.01" min="0" value="{{ old('valor_desconto') ?? $notaFiscal->valor_desconto }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="valor_outras_despesas">Outras Despesas</label>
                                        <input type="number" name="valor_outras_despesas" id="valor_outras_despesas" class="form-control" step="0.01" min="0" value="{{ old('valor_outras_despesas') ?? $notaFiscal->valor_outras_despesas }}">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info"></i> Resumo</h5>
                                <p class="mb-1">Valor dos Produtos: <span id="total-produtos">R$ 0,00</span></p>
                                <p class="mb-1">Valor Total: <span id="total-geral">R$ 0,00</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar Nota Fiscal
                    </button>
                    <a href="{{ route('notas-fiscais.show', $notaFiscal) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Template para novo item -->
<template id="template-item">
    <tr class="item-row">
        <td>
            <input type="text" name="itens[INDEX][codigo_produto]" class="form-control form-control-sm" required>
        </td>
        <td>
            <input type="text" name="itens[INDEX][descricao]" class="form-control form-control-sm" required>
        </td>
        <td>
            <input type="text" name="itens[INDEX][cfop]" class="form-control form-control-sm" maxlength="4" required>
        </td>
        <td>
            <input type="text" name="itens[INDEX][unidade]" class="form-control form-control-sm" value="UN" maxlength="10">
        </td>
        <td>
            <input type="number" name="itens[INDEX][quantidade]" class="form-control form-control-sm quantidade" step="0.0001" min="0.0001" required>
        </td>
        <td>
            <input type="number" name="itens[INDEX][valor_unitario]" class="form-control form-control-sm valor-unitario" step="0.01" min="0.01" required>
        </td>
        <td>
            <input type="number" name="itens[INDEX][valor_total]" class="form-control form-control-sm valor-total" step="0.01" readonly>
        </td>
        <td>
            <input type="number" name="itens[INDEX][icms_aliquota]" class="form-control form-control-sm" step="0.01" min="0" max="100" value="0">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remover-item">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let itemIndex = {{ $notaFiscal->itens->count() }};

    // Adicionar novo item
    $('#adicionar-item').click(function() {
        let template = $('#template-item').html();
        template = template.replace(/INDEX/g, itemIndex);
        $('#itens-container').append(template);
        itemIndex++;
        calcularTotais();
    });

    // Remover item
    $(document).on('click', '.remover-item', function() {
        $(this).closest('tr').remove();
        calcularTotais();
    });

    // Calcular valor total do item
    $(document).on('input', '.quantidade, .valor-unitario', function() {
        let row = $(this).closest('tr');
        let quantidade = parseFloat(row.find('.quantidade').val()) || 0;
        let valorUnitario = parseFloat(row.find('.valor-unitario').val()) || 0;
        let valorTotal = quantidade * valorUnitario;

        row.find('.valor-total').val(valorTotal.toFixed(2));
        calcularTotais();
    });

    // Calcular totais gerais
    function calcularTotais() {
        let totalProdutos = 0;

        $('.valor-total').each(function() {
            totalProdutos += parseFloat($(this).val()) || 0;
        });

        let frete = parseFloat($('#valor_frete').val()) || 0;
        let seguro = parseFloat($('#valor_seguro').val()) || 0;
        let desconto = parseFloat($('#valor_desconto').val()) || 0;
        let outrasDespesas = parseFloat($('#valor_outras_despesas').val()) || 0;

        let totalGeral = totalProdutos + frete + seguro + outrasDespesas - desconto;

        $('#total-produtos').text('R$ ' + totalProdutos.toLocaleString('pt-BR', {minimumFractionDigits: 2}));
        $('#total-geral').text('R$ ' + totalGeral.toLocaleString('pt-BR', {minimumFractionDigits: 2}));
    }

    // Recalcular quando valores adicionais mudarem
    $('#valor_frete, #valor_seguro, #valor_desconto, #valor_outras_despesas').on('input', calcularTotais);

    // Calcular totais iniciais
    calcularTotais();

    // Validação do formulário
    $('#form-nota-fiscal').submit(function(e) {
        if ($('#itens-container tr').length === 0) {
            e.preventDefault();
            alert('É necessário ter pelo menos um item na nota fiscal.');
            return false;
        }
    });
});
</script>
@endsection
