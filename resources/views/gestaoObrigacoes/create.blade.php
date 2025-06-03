@extends('app')

@section('title', 'Cadastrar Obrigação')

@section('content')
    <form method="POST" action="{{ route('obrigacoes.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Empresa</label>
                <select name="empresa_id" class="form-select" required>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}">{{ $empresa->razao_social }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo de Obrigação</label>
                <input type="text" name="tipo" class="form-control" placeholder="Ex: SPED, DCTF" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Frequência</label>
                <select name="frequencia" class="form-select" required>
                    <option value="mensal">Mensal</option>
                    <option value="trimestral">Trimestral</option>
                    <option value="anual">Anual</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Data de Início</label>
                <input type="date" name="data_inicio" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Data de Vencimento</label>
                <input type="date" name="data_vencimento" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pendente">Pendente</option>
                    <option value="em andamento">Em andamento</option>
                    <option value="concluida">Concluída</option>
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="text-end mt-3">
            <button class="btn btn-info" type="submit">Salvar Obrigação</button>
        </div>
    </form>
@endsection
