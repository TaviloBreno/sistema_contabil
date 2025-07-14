<?php

namespace App\Http\Controllers;

use App\Models\DocumentoUpload;
use App\Models\Empresa;
use App\Models\Obrigacao;
use App\Models\NotaFiscal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentoUploadController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentoUpload::with(['empresa', 'user']);

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome_original', 'like', '%' . $request->busca . '%')
                  ->orWhere('descricao', 'like', '%' . $request->busca . '%');
            });
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(20);
        $empresas = Empresa::all();

        return view('documentos.index', compact('documentos', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::all();
        $categorias = [
            'nfe' => 'Nota Fiscal Eletrônica',
            'contrato' => 'Contratos',
            'certidao' => 'Certidões',
            'licenca' => 'Licenças',
            'comprovante' => 'Comprovantes',
            'relatorio' => 'Relatórios',
            'outros' => 'Outros'
        ];

        return view('documentos.create', compact('empresas', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|max:10240|mimes:pdf,xml,xlsx,xls,doc,docx,jpg,jpeg,png,gif,zip,rar,txt,csv',
            'categoria' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id',
            'descricao' => 'nullable|string|max:1000',
            'tags' => 'nullable|string',
            'vinculado_type' => 'nullable|string',
            'vinculado_id' => 'nullable|integer'
        ]);

        $arquivo = $request->file('arquivo');
        $nomeOriginal = $arquivo->getClientOriginalName();
        $extensao = $arquivo->getClientOriginalExtension();
        $nomeArquivo = Str::uuid() . '.' . $extensao;
        $hashArquivo = md5_file($arquivo->getRealPath());

        // Verificar se já existe um arquivo com o mesmo hash
        $arquivoExistente = DocumentoUpload::where('hash_arquivo', $hashArquivo)
            ->where('empresa_id', $request->empresa_id)
            ->first();

        if ($arquivoExistente) {
            return redirect()->back()->with('error', 'Este arquivo já foi enviado anteriormente.');
        }

        $caminho = $arquivo->storeAs('documentos', $nomeArquivo, 'public');

        $tags = $request->tags ? explode(',', $request->tags) : [];
        $tags = array_map('trim', $tags);

        $documento = DocumentoUpload::create([
            'nome_original' => $nomeOriginal,
            'nome_arquivo' => $nomeArquivo,
            'tipo_arquivo' => $arquivo->getMimeType(),
            'categoria' => $request->categoria,
            'tamanho' => $arquivo->getSize(),
            'caminho' => $caminho,
            'hash_arquivo' => $hashArquivo,
            'tags' => $tags,
            'descricao' => $request->descricao,
            'vinculado_type' => $request->vinculado_type,
            'vinculado_id' => $request->vinculado_id,
            'empresa_id' => $request->empresa_id,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('documentos.index')
            ->with('success', 'Documento enviado com sucesso!');
    }

    public function show(DocumentoUpload $documento)
    {
        $documento->load(['empresa', 'user', 'vinculado']);
        return view('documentos.show', compact('documento'));
    }

    public function edit(DocumentoUpload $documento)
    {
        $empresas = Empresa::all();
        $categorias = [
            'nfe' => 'Nota Fiscal Eletrônica',
            'contrato' => 'Contratos',
            'certidao' => 'Certidões',
            'licenca' => 'Licenças',
            'comprovante' => 'Comprovantes',
            'relatorio' => 'Relatórios',
            'outros' => 'Outros'
        ];

        return view('documentos.edit', compact('documento', 'empresas', 'categorias'));
    }

    public function update(Request $request, DocumentoUpload $documento)
    {
        $request->validate([
            'categoria' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id',
            'descricao' => 'nullable|string|max:1000',
            'tags' => 'nullable|string',
            'vinculado_type' => 'nullable|string',
            'vinculado_id' => 'nullable|integer'
        ]);

        $tags = $request->tags ? explode(',', $request->tags) : [];
        $tags = array_map('trim', $tags);

        $documento->update([
            'categoria' => $request->categoria,
            'tags' => $tags,
            'descricao' => $request->descricao,
            'vinculado_type' => $request->vinculado_type,
            'vinculado_id' => $request->vinculado_id,
            'empresa_id' => $request->empresa_id
        ]);

        return redirect()->route('documentos.index')
            ->with('success', 'Documento atualizado com sucesso!');
    }

    public function destroy(DocumentoUpload $documento)
    {
        $documento->delete();

        return redirect()->route('documentos.index')
            ->with('success', 'Documento excluído com sucesso!');
    }

    public function download(DocumentoUpload $documento)
    {
        if (!Storage::disk('public')->exists($documento->caminho)) {
            abort(404, 'Arquivo não encontrado');
        }

        return response()->download(
            storage_path('app/public/' . $documento->caminho),
            $documento->nome_original
        );
    }

    public function vincularObrigacao(Request $request, DocumentoUpload $documento)
    {
        $request->validate([
            'obrigacao_id' => 'required|exists:obrigacoes,id'
        ]);

        $documento->update([
            'vinculado_type' => Obrigacao::class,
            'vinculado_id' => $request->obrigacao_id
        ]);

        return response()->json(['success' => true]);
    }

    public function vincularNotaFiscal(Request $request, DocumentoUpload $documento)
    {
        $request->validate([
            'nota_fiscal_id' => 'required|exists:notas_fiscais,id'
        ]);

        $documento->update([
            'vinculado_type' => NotaFiscal::class,
            'vinculado_id' => $request->nota_fiscal_id
        ]);

        return response()->json(['success' => true]);
    }
}
