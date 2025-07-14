@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalhes do Log</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('logs.index') }}">Logs</a></li>
                    <li class="breadcrumb-item active">Detalhes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações da Atividade</h3>
                        <div class="card-tools">
                            <a href="{{ route('logs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informações Básicas</h5>
                                <table class="table table-striped">
                                    <tr>
                                        <td><strong>Data/Hora:</strong></td>
                                        <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Usuário:</strong></td>
                                        <td>
                                            @if($activity->causer)
                                                {{ $activity->causer->name }} ({{ $activity->causer->email }})
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ação:</strong></td>
                                        <td>
                                            @if($activity->description == 'created')
                                                <span class="badge badge-success">Criado</span>
                                            @elseif($activity->description == 'updated')
                                                <span class="badge badge-warning">Atualizado</span>
                                            @elseif($activity->description == 'deleted')
                                                <span class="badge badge-danger">Excluído</span>
                                            @else
                                                <span class="badge badge-info">{{ $activity->description }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Modelo:</strong></td>
                                        <td>{{ $activity->subject_type ? class_basename($activity->subject_type) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ID do Registro:</strong></td>
                                        <td>{{ $activity->subject_id ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Dados do Registro</h5>
                                @if($activity->subject)
                                    <div class="card">
                                        <div class="card-body">
                                            @if($activity->subject_type == 'App\Models\NotaFiscal')
                                                <p><strong>Número:</strong> {{ $activity->subject->numero_nf }}</p>
                                                <p><strong>Série:</strong> {{ $activity->subject->serie }}</p>
                                                <p><strong>Status:</strong> {{ $activity->subject->status }}</p>
                                                <p><strong>Valor:</strong> R$ {{ number_format($activity->subject->valor_total, 2, ',', '.') }}</p>
                                            @elseif($activity->subject_type == 'App\Models\Empresa')
                                                <p><strong>Razão Social:</strong> {{ $activity->subject->razao_social }}</p>
                                                <p><strong>CNPJ:</strong> {{ $activity->subject->cnpj }}</p>
                                                <p><strong>Status:</strong> {{ $activity->subject->status }}</p>
                                            @elseif($activity->subject_type == 'App\Models\DocumentoUpload')
                                                <p><strong>Nome:</strong> {{ $activity->subject->nome_original }}</p>
                                                <p><strong>Categoria:</strong> {{ $activity->subject->categoria }}</p>
                                                <p><strong>Tamanho:</strong> {{ $activity->subject->tamanho_formatado }}</p>
                                            @else
                                                <p class="text-muted">Detalhes não disponíveis</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        Registro não encontrado (pode ter sido excluído)
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($activity->properties && count($activity->properties) > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Propriedades Alteradas</h5>
                                <div class="card">
                                    <div class="card-body">
                                        @if(isset($activity->properties['old']) && isset($activity->properties['attributes']))
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Valores Antigos</h6>
                                                    <pre class="bg-light p-3">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Valores Novos</h6>
                                                    <pre class="bg-light p-3">{{ json_encode($activity->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            </div>
                                        @else
                                            <pre class="bg-light p-3">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
