<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Http\Requests\EmpresaRequest;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('empresas.index', [
            'empresas' => Empresa::with('matriz')->paginate(10),
        ]);
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
}
