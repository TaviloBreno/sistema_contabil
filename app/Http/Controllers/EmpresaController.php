<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Http\Requests\EmpresaRequest;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::with('matriz')->orderBy('razao_social')->paginate(10);
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create', [
            'matrizes' => Empresa::whereNull('matriz_id')->get()
        ]);
    }

    public function store(EmpresaRequest $request)
    {
        Empresa::create($request->validated());
        return redirect()->route('empresas.index')->with('success', 'Empresa cadastrada com sucesso.');
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', [
            'empresa' => $empresa,
            'matrizes' => Empresa::whereNull('matriz_id')->get()
        ]);
    }

    public function update(EmpresaRequest $request, Empresa $empresa)
    {
        $empresa->update($request->validated());
        return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', 'Empresa excluída com sucesso.');
    }
}
