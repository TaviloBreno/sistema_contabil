<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obrigacao;
use App\Models\Empresa;

class ObrigacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Obrigacao::with('empresa')->orderByDesc('data_vencimento');

        if ($request->filled('tipo')) {
            $query->where('tipo', 'like', '%' . $request->tipo . '%');
        }

        if ($request->filled('frequencia')) {
            $query->where('frequencia', $request->frequencia);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        $obrigacoes = $query->paginate(10)->withQueryString();
        $empresas = Empresa::orderBy('razao_social')->get();

        return view('gestaoObrigacoes.index', compact('obrigacoes', 'empresas'));
    }


    public function create()
    {
        $empresas = Empresa::orderBy('razao_social')->get();
        return view('gestaoObrigacoes.create', compact('empresas'));
    }

    // Salvar nova obrigação
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'tipo' => 'required|string|max:100',
            'frequencia' => 'required|in:mensal,trimestral,anual',
            'data_inicio' => 'required|date',
            'data_vencimento' => 'required|date|after_or_equal:data_inicio',
            'status' => 'required|in:pendente,em andamento,concluida',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        Obrigacao::create([
            'empresa_id'     => $request->empresa_id,
            'tipo'           => $request->tipo,
            'frequencia'     => $request->frequencia,
            'data_inicio'    => $request->data_inicio,
            'data_vencimento' => $request->data_vencimento,
            'status'         => $request->status,
            'observacoes'    => $request->observacoes,
            'user_id'        => auth()->id(),
        ]);

        return redirect()->route('obrigacoes.index')->with('success', 'Obrigação cadastrada com sucesso!');
    }

    // Editar obrigação existente
    public function edit(Obrigacao $obrigacao)
    {
        $empresas = Empresa::orderBy('razao_social')->get();
        return view('gestaoObrigacoes.edit', compact('obrigacao', 'empresas'));
    }

    // Atualizar obrigação
    public function update(Request $request, Obrigacao $obrigacao)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'tipo' => 'required|string|max:100',
            'frequencia' => 'required|in:mensal,trimestral,anual',
            'data_inicio' => 'required|date',
            'data_vencimento' => 'required|date|after_or_equal:data_inicio',
            'status' => 'required|in:pendente,em andamento,concluida',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $obrigacao->update($request->all());
        return redirect()->route('obrigacoes.index')->with('success', 'Obrigação atualizada com sucesso!');
    }

    // Exibir detalhes da obrigação
    public function show(Obrigacao $obrigacao)
    {
        return view('gestaoObrigacoes.show', compact('obrigacao'));
    }

    // Excluir obrigação
    public function destroy(Obrigacao $obrigacao)
    {
        $obrigacao->delete();
        return redirect()->route('obrigacoes.index')->with('success', 'Obrigação excluída com sucesso!');
    }
}
