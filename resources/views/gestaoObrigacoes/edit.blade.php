@extends('app')

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0" style="font-size: 1.5rem;">Editar Obrigação</h2>
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('obrigacoes.update', $obrigacao->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Empresa --}}
                <div class="mb-4">
                    <label for="empresa_id" class="form-label fw-semibold">Empresa</label>
                    <select name="empresa_id" id="empresa_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}"
                            {{ old('empresa_id', $obrigacao->empresa_id) == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->razao_social }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo --}}
                <div class="mb-4">
                    <label for="tipo" class="form-label fw-semibold">Tipo</label>
                    <input type="text" name="tipo" id="tipo" class="form-control"
                        value="{{ old('tipo', $obrigacao->tipo) }}" required>
                </div>

                {{-- Frequência --}}
                <div class="mb-4">
                    <label for="frequencia" class="form-label fw-semibold">Frequência</label>
                    <select name="frequencia" id="frequencia" class="form-select" required>
                        @foreach(['mensal', 'trimestral', 'anual'] as $freq)
                        <option value="{{ $freq }}"
                            {{ old('frequencia', $obrigacao->frequencia) == $freq ? 'selected' : '' }}>
                            {{ ucfirst($freq) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Data de Início --}}
                <div class="mb-4">
                    <label for="data_inicio" class="form-label fw-semibold">Data de Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control"
                        value="{{ old('data_inicio', $obrigacao->data_inicio) }}" required>
                </div>

                {{-- Data de Vencimento --}}
                <div class="mb-4">
                    <label for="data_vencimento" class="form-label fw-semibold">Data de Vencimento</label>
                    <input type="date" name="data_vencimento" id="data_vencimento" class="form-control"
                        value="{{ old('data_vencimento', $obrigacao->data_vencimento) }}" required>
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        @foreach(['pendente', 'em andamento', 'concluida'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $obrigacao->status) == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Observações --}}
                <div class="mb-4">
                    <label for="observacoes" class="form-label fw-semibold">Observações</label>
                    <textarea name="observacoes" id="observacoes" class="form-control" rows="3">{{ old('observacoes', $obrigacao->observacoes) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">Salvar</button>
                    <a href="{{ route('obrigacoes.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
