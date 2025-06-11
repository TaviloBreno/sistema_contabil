<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use Illuminate\Http\Request;

class ConfiguracaoSistemaController extends Controller
{
    public function index()
    {
        $configuracoes = Configuracao::orderBy('chave')->get();
        return view('configuracoes.index', compact('configuracoes'));
    }

    public function store(Request $request)
    {
        foreach ($request->input('configuracoes', []) as $id => $valor) {
            Configuracao::where('id', $id)->update(['valor' => $valor]);
        }

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
