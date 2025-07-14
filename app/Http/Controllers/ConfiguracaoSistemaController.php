<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use Illuminate\Http\Request;

class ConfiguracaoSistemaController extends Controller
{
    /**
     * Exibe a tela de configurações agrupadas por grupo.
     */
    public function index()
    {
        $configuracoes = Configuracao::all()->groupBy('grupo');

        return view('configuracoes.index', compact('configuracoes'));
    }

    /**
     * Salva as configurações.
     */
    public function store(Request $request)
    {
        $configs = $request->input('configuracoes', []);

        foreach ($configs as $id => $valor) {
            $config = Configuracao::find($id);

            if ($config) {
                // Se o tipo for booleano (checkbox), define como 0 ou 1
                if ($config->tipo === 'boolean') {
                    $valor = isset($valor) && ($valor === 'on' || $valor == 1 || $valor === true) ? 1 : 0;
                }

                $config->update(['valor' => $valor]);
            }
        }

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
