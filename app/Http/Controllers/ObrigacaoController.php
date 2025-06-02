<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ObrigacaoController extends Controller
{
    public function index()
    {
        $obrigacoes = Obrigacao::with('empresa')->orderByDesc('data_vencimento')->paginate(10);
        return view('obrigacoes.index', compact('obrigacoes'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('razao_social')->get();
        return view('obrigacoes.create', compact('empresas'));
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

        Obrigacao::create($request->all());

        return redirect()->route('obrigacoes.index')->with('success', 'Obrigação cadastrada com sucesso!');
    }
}
