<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Obrigacao;
use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    /**
     * Exibe a listagem de documentos com paginação.
     */
    public function index()
    {
        $documentos = Documento::with(['empresa', 'obrigacao'])->latest()->paginate(10);
        $empresas = Empresa::all();
        $obrigacoes = Obrigacao::all();

        return view('documentos.index', compact('documentos', 'empresas', 'obrigacoes'));
    }

    /**
     * Armazena um novo documento enviado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'obrigacao_id' => 'nullable|exists:obrigacoes,id',
            'arquivo' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
        ]);

        $arquivo = $request->file('arquivo');
        $nomeOriginal = $arquivo->getClientOriginalName();
        $caminho = $arquivo->store('documentos');
        $protocolo = strtoupper(Str::random(10));

        Documento::create([
            'empresa_id'     => $request->empresa_id,
            'obrigacao_id'   => $request->obrigacao_id,
            'nome_arquivo'   => $nomeOriginal,
            'caminho_arquivo'=> $caminho,
            'protocolo'      => $protocolo,
        ]);

        return back()->with('success', "Documento enviado com sucesso. Protocolo: {$protocolo}");
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
