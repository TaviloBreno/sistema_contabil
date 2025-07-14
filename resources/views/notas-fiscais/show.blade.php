@extends('app')

@section('title', 'Visualizar Nota Fiscal')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Nota Fiscal {{ $notaFiscal->numero_nf }}/{{ $notaFiscal->serie }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('notas-fiscais.index') }}">Notas Fiscais</a></li>
                    <li class="breadcrumb-item active">Visualizar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Status e Ações -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Status: {!! $notaFiscal->status_badge !!}
                    Tipo: {!! $notaFiscal->tipo_badge !!}
                </h3>
                <div class="card-tools">
                    @if($notaFiscal->status === 'rascunho')
                        <a href="{{ route('notas-fiscais.edit', $notaFiscal) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('notas-fiscais.autorizar', $notaFiscal) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja autorizar esta nota fiscal?')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Autorizar
                            </button>
                        </form>
                    @endif

                    @if($notaFiscal->status === 'autorizada')
                        <a href="{{ route('notas-fiscais.xml', $notaFiscal) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-code"></i> Download XML
                        </a>
                        <a href="{{ route('notas-fiscais.danfe', $notaFiscal) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> DANFE
                        </a>
                        <form action="{{ route('notas-fiscais.cancelar', $notaFiscal) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja cancelar esta nota fiscal?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-ban"></i> Cancelar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Dados da Nota Fiscal -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dados da Nota Fiscal</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Número:</strong></td>
                                <td>{{ $notaFiscal->numero_nf }}</td>
                            </tr>
                            <tr>
                                <td><strong>Série:</strong></td>
                                <td>{{ $notaFiscal->serie }}</td>
                            </tr>
                            <tr>
                                <td><strong>Modelo:</strong></td>
                                <td>{{ $notaFiscal->modelo }}</td>
                            </tr>
                            <tr>
                                <td><strong>Data Emissão:</strong></td>
                                <td>{{ $notaFiscal->data_emissao->format('d/m/Y') }}</td>
                            </tr>
                            @if($notaFiscal->data_saida)
                            <tr>
                                <td><strong>Data Saída:</strong></td>
                                <td>{{ $notaFiscal->data_saida->format('d/m/Y') }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->chave_acesso)
                            <tr>
                                <td><strong>Chave de Acesso:</strong></td>
                                <td>{{ $notaFiscal->chave_acesso }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->protocolo_autorizacao)
                            <tr>
                                <td><strong>Protocolo:</strong></td>
                                <td>{{ $notaFiscal->protocolo_autorizacao }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Criada por:</strong></td>
                                <td>{{ $notaFiscal->user->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Dados da Empresa -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Empresa Emitente</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Razão Social:</strong></td>
                                <td>{{ $notaFiscal->empresa->razao_social }}</td>
                            </tr>
                            <tr>
                                <td><strong>CNPJ:</strong></td>
                                <td>{{ $notaFiscal->empresa->cnpj }}</td>
                            </tr>
                            <tr>
                                <td><strong>Regime Tributário:</strong></td>
                                <td>{{ $notaFiscal->empresa->regime_tributario }}</td>
                            </tr>
                        </table>
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
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nome/Razão Social:</strong></td>
                                <td>{{ $notaFiscal->destinatario_nome }}</td>
                            </tr>
                            <tr>
                                <td><strong>CPF/CNPJ:</strong></td>
                                <td>{{ $notaFiscal->destinatario_documento }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        @if($notaFiscal->destinatario_endereco)
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Endereço:</strong></td>
                                <td>{{ $notaFiscal->destinatario_endereco }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cidade/UF:</strong></td>
                                <td>{{ $notaFiscal->destinatario_cidade }} - {{ $notaFiscal->destinatario_uf }}</td>
                            </tr>
                            <tr>
                                <td><strong>CEP:</strong></td>
                                <td>{{ $notaFiscal->destinatario_cep }}</td>
                            </tr>
                        </table>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if($notaFiscal->destinatario_telefone || $notaFiscal->destinatario_email)
                        <table class="table table-borderless">
                            @if($notaFiscal->destinatario_telefone)
                            <tr>
                                <td><strong>Telefone:</strong></td>
                                <td>{{ $notaFiscal->destinatario_telefone }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->destinatario_email)
                            <tr>
                                <td><strong>E-mail:</strong></td>
                                <td>{{ $notaFiscal->destinatario_email }}</td>
                            </tr>
                            @endif
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Itens da Nota Fiscal -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Itens da Nota Fiscal</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>CFOP</th>
                            <th>Un</th>
                            <th>Qtd</th>
                            <th>Vlr Unit</th>
                            <th>Vlr Total</th>
                            <th>ICMS</th>
                            <th>IPI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notaFiscal->itens as $item)
                        <tr>
                            <td>{{ $item->numero_item }}</td>
                            <td>{{ $item->codigo_produto }}</td>
                            <td>{{ $item->descricao }}</td>
                            <td>{{ $item->cfop }}</td>
                            <td>{{ $item->unidade }}</td>
                            <td>{{ number_format($item->quantidade, 4, ',', '.') }}</td>
                            <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                            <td>
                                @if($item->icms_valor > 0)
                                    {{ number_format($item->icms_aliquota, 2) }}%<br>
                                    R$ {{ number_format($item->icms_valor, 2, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($item->ipi_valor > 0)
                                    {{ number_format($item->ipi_aliquota, 2) }}%<br>
                                    R$ {{ number_format($item->ipi_valor, 2, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totais -->
        <div class="row">
            <div class="col-md-6">
                @if($notaFiscal->observacoes)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Observações</h3>
                    </div>
                    <div class="card-body">
                        {{ $notaFiscal->observacoes }}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Totais da Nota Fiscal</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Valor dos Produtos:</strong></td>
                                <td class="text-right">R$ {{ number_format($notaFiscal->valor_produtos, 2, ',', '.') }}</td>
                            </tr>
                            @if($notaFiscal->valor_frete > 0)
                            <tr>
                                <td>Valor do Frete:</td>
                                <td class="text-right">R$ {{ number_format($notaFiscal->valor_frete, 2, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->valor_seguro > 0)
                            <tr>
                                <td>Valor do Seguro:</td>
                                <td class="text-right">R$ {{ number_format($notaFiscal->valor_seguro, 2, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->valor_desconto > 0)
                            <tr>
                                <td>Valor do Desconto:</td>
                                <td class="text-right">- R$ {{ number_format($notaFiscal->valor_desconto, 2, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->valor_outras_despesas > 0)
                            <tr>
                                <td>Outras Despesas:</td>
                                <td class="text-right">R$ {{ number_format($notaFiscal->valor_outras_despesas, 2, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->valor_ipi > 0)
                            <tr>
                                <td>Total do IPI:</td>
                                <td class="text-right">R$ {{ number_format($notaFiscal->valor_ipi, 2, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($notaFiscal->valor_icms > 0)
                            <tr>
                                <td>Total do ICMS:</td>
                                <td class="text-right">R$ {{ number_format($notaFiscal->valor_icms, 2, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr class="table-active">
                                <td><strong>VALOR TOTAL DA NOTA:</strong></td>
                                <td class="text-right"><strong>R$ {{ number_format($notaFiscal->valor_total, 2, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="card">
            <div class="card-footer text-center">
                <a href="{{ route('notas-fiscais.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar à Lista
                </a>
                @if($notaFiscal->status === 'rascunho')
                    <a href="{{ route('notas-fiscais.edit', $notaFiscal) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
