@extends('app')

@section('title', 'DANFE - Nota Fiscal ' . $notaFiscal->numero_nf)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">DANFE - Documento Auxiliar da Nota Fiscal Eletrônica</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                    <button onclick="window.close()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="danfe-container" style="max-width: 800px; margin: 0 auto; background: white; padding: 20px; border: 1px solid #ccc;">

            <!-- Cabeçalho -->
            <div class="row border" style="min-height: 150px;">
                <div class="col-3 border-right p-2">
                    <div class="text-center">
                        <strong>{{ $notaFiscal->empresa->razao_social }}</strong><br>
                        <small>{{ $notaFiscal->empresa->cnpj }}</small>
                    </div>
                </div>
                <div class="col-6 border-right p-2 text-center">
                    <h4><strong>DANFE</strong></h4>
                    <p class="mb-0"><strong>Documento Auxiliar da Nota Fiscal Eletrônica</strong></p>
                    <p class="mb-0">{{ $notaFiscal->tipo == 'entrada' ? '0-ENTRADA' : '1-SAÍDA' }}</p>
                    <p class="mb-0">Nº {{ str_pad($notaFiscal->numero_nf, 9, '0', STR_PAD_LEFT) }}</p>
                    <p class="mb-0">Série {{ $notaFiscal->serie }}</p>
                    <p class="mb-0">Folha 1/1</p>
                </div>
                <div class="col-3 p-2">
                    @if($notaFiscal->chave_acesso)
                        <div class="text-center">
                            <div style="font-size: 10px; line-height: 1.2;">
                                <strong>Chave de Acesso:</strong><br>
                                {{ chunk_split($notaFiscal->chave_acesso, 4, ' ') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dados do Destinatário/Remetente -->
            <div class="border border-top-0">
                <div class="bg-light p-1">
                    <strong>DESTINATÁRIO/REMETENTE</strong>
                </div>
                <div class="p-2">
                    <div class="row">
                        <div class="col-8">
                            <strong>Nome/Razão Social:</strong> {{ $notaFiscal->destinatario_nome }}<br>
                            @if($notaFiscal->destinatario_endereco)
                                <strong>Endereço:</strong> {{ $notaFiscal->destinatario_endereco }}<br>
                            @endif
                            @if($notaFiscal->destinatario_cidade)
                                <strong>Município:</strong> {{ $notaFiscal->destinatario_cidade }} - {{ $notaFiscal->destinatario_uf }}<br>
                            @endif
                        </div>
                        <div class="col-4">
                            <strong>CNPJ/CPF:</strong> {{ $notaFiscal->destinatario_documento }}<br>
                            <strong>Data da Emissão:</strong> {{ $notaFiscal->data_emissao->format('d/m/Y') }}<br>
                            @if($notaFiscal->destinatario_cep)
                                <strong>CEP:</strong> {{ $notaFiscal->destinatario_cep }}<br>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cálculo do Imposto -->
            <div class="border border-top-0">
                <div class="bg-light p-1">
                    <strong>CÁLCULO DO IMPOSTO</strong>
                </div>
                <div class="p-2">
                    <div class="row">
                        <div class="col-3">
                            <strong>Base de Cálculo do ICMS:</strong><br>
                            R$ {{ number_format($notaFiscal->itens->sum('icms_base_calculo'), 2, ',', '.') }}
                        </div>
                        <div class="col-3">
                            <strong>Valor do ICMS:</strong><br>
                            R$ {{ number_format($notaFiscal->valor_icms, 2, ',', '.') }}
                        </div>
                        <div class="col-3">
                            <strong>Valor Total dos Produtos:</strong><br>
                            R$ {{ number_format($notaFiscal->valor_produtos, 2, ',', '.') }}
                        </div>
                        <div class="col-3">
                            <strong>Valor do Frete:</strong><br>
                            R$ {{ number_format($notaFiscal->valor_frete, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-3">
                            <strong>Valor do Seguro:</strong><br>
                            R$ {{ number_format($notaFiscal->valor_seguro, 2, ',', '.') }}
                        </div>
                        <div class="col-3">
                            <strong>Desconto:</strong><br>
                            R$ {{ number_format($notaFiscal->valor_desconto, 2, ',', '.') }}
                        </div>
                        <div class="col-3">
                            <strong>Outras Despesas:</strong><br>
                            R$ {{ number_format($notaFiscal->valor_outras_despesas, 2, ',', '.') }}
                        </div>
                        <div class="col-3">
                            <strong>Valor Total da NF:</strong><br>
                            <strong>R$ {{ number_format($notaFiscal->valor_total, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados dos Produtos/Serviços -->
            <div class="border border-top-0">
                <div class="bg-light p-1">
                    <strong>DADOS DOS PRODUTOS/SERVIÇOS</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0" style="font-size: 11px;">
                        <thead class="bg-light">
                            <tr>
                                <th>Cód Prod</th>
                                <th>Descrição do Produto/Serviço</th>
                                <th>NCM/SH</th>
                                <th>CFOP</th>
                                <th>Un</th>
                                <th>Qtde</th>
                                <th>Vl Unit</th>
                                <th>Vl Total</th>
                                <th>BC ICMS</th>
                                <th>Vl ICMS</th>
                                <th>Vl IPI</th>
                                <th>% ICMS</th>
                                <th>% IPI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notaFiscal->itens as $item)
                            <tr>
                                <td>{{ $item->codigo_produto }}</td>
                                <td>{{ $item->descricao }}</td>
                                <td>{{ $item->ncm ?? '' }}</td>
                                <td>{{ $item->cfop }}</td>
                                <td>{{ $item->unidade }}</td>
                                <td>{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->icms_base_calculo, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->icms_valor, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->ipi_valor, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->icms_aliquota, 1) }}%</td>
                                <td>{{ number_format($item->ipi_aliquota, 1) }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Informações Complementares -->
            @if($notaFiscal->observacoes || $notaFiscal->chave_acesso)
            <div class="border border-top-0">
                <div class="bg-light p-1">
                    <strong>INFORMAÇÕES COMPLEMENTARES</strong>
                </div>
                <div class="p-2" style="min-height: 80px;">
                    @if($notaFiscal->observacoes)
                        <p class="mb-2">{{ $notaFiscal->observacoes }}</p>
                    @endif
                    @if($notaFiscal->protocolo_autorizacao)
                        <p class="mb-0"><small>
                            <strong>Protocolo de Autorização:</strong> {{ $notaFiscal->protocolo_autorizacao }} -
                            {{ $notaFiscal->data_autorizacao->format('d/m/Y H:i:s') }}
                        </small></p>
                    @endif
                </div>
            </div>
            @endif

            <!-- QR Code (Simulado para NFCe) -->
            @if($notaFiscal->modelo == '65')
            <div class="border border-top-0 text-center p-3">
                <div class="bg-light p-1 mb-2">
                    <strong>Consulta via leitor de QR Code</strong>
                </div>
                <div style="width: 150px; height: 150px; background: #f0f0f0; margin: 0 auto; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-qrcode fa-3x text-muted"></i>
                </div>
                <p class="mt-2 mb-0" style="font-size: 10px;">
                    Consulte pela Chave de Acesso em<br>
                    http://nfce.sefaz.gov.br
                </p>
            </div>
            @endif

            <!-- Rodapé -->
            <div class="border border-top-0 p-2 text-center" style="font-size: 10px;">
                <p class="mb-0">
                    <strong>EMITENTE:</strong> {{ $notaFiscal->empresa->razao_social }} -
                    CNPJ: {{ $notaFiscal->empresa->cnpj }}
                </p>
                @if($notaFiscal->chave_acesso)
                <p class="mb-0">
                    <strong>Chave de Acesso:</strong> {{ $notaFiscal->chave_acesso }}
                </p>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
@media print {
    .content-header,
    .btn,
    .sidebar,
    .main-header,
    .main-footer {
        display: none !important;
    }

    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }

    .danfe-container {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        page-break-inside: avoid;
    }

    body {
        font-size: 12px !important;
    }

    .table-sm {
        font-size: 10px !important;
    }
}
</style>
@endsection
