<?php

namespace App\Http\Controllers;

use App\Models\NotaFiscal;
use App\Models\ItemNotaFiscal;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotaFiscalController extends Controller
{
    public function index(Request $request)
    {
        $query = NotaFiscal::with(['empresa', 'user'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_emissao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_emissao', '<=', $request->data_fim);
        }

        $notasFiscais = $query->paginate(20);
        $empresas = Empresa::orderBy('razao_social')->get();

        return view('notas-fiscais.index', compact('notasFiscais', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('razao_social')->get();
        return view('notas-fiscais.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'empresa_id' => 'required|exists:empresas,id',
            'tipo' => 'required|in:entrada,saida',
            'destinatario_nome' => 'required|string|max:255',
            'destinatario_documento' => 'required|string|max:20',
            'data_emissao' => 'required|date',
            'itens' => 'required|array|min:1',
            'itens.*.codigo_produto' => 'required|string|max:50',
            'itens.*.descricao' => 'required|string|max:255',
            'itens.*.cfop' => 'required|string|size:4',
            'itens.*.quantidade' => 'required|numeric|min:0.0001',
            'itens.*.valor_unitario' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $notaFiscal = NotaFiscal::create([
                'empresa_id' => $request->empresa_id,
                'tipo' => $request->tipo,
                'serie' => $request->serie ?? '001',
                'modelo' => $request->modelo ?? '55',
                'destinatario_nome' => $request->destinatario_nome,
                'destinatario_documento' => $request->destinatario_documento,
                'destinatario_endereco' => $request->destinatario_endereco,
                'destinatario_cidade' => $request->destinatario_cidade,
                'destinatario_uf' => $request->destinatario_uf,
                'destinatario_cep' => $request->destinatario_cep,
                'destinatario_telefone' => $request->destinatario_telefone,
                'destinatario_email' => $request->destinatario_email,
                'data_emissao' => $request->data_emissao,
                'data_saida' => $request->data_saida,
                'valor_frete' => $request->valor_frete ?? 0,
                'valor_seguro' => $request->valor_seguro ?? 0,
                'valor_desconto' => $request->valor_desconto ?? 0,
                'valor_outras_despesas' => $request->valor_outras_despesas ?? 0,
                'observacoes' => $request->observacoes,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->itens as $itemData) {
                $valorTotal = $itemData['quantidade'] * $itemData['valor_unitario'];

                $item = ItemNotaFiscal::create([
                    'nota_fiscal_id' => $notaFiscal->id,
                    'codigo_produto' => $itemData['codigo_produto'],
                    'descricao' => $itemData['descricao'],
                    'ncm' => $itemData['ncm'] ?? null,
                    'cfop' => $itemData['cfop'],
                    'unidade' => $itemData['unidade'] ?? 'UN',
                    'quantidade' => $itemData['quantidade'],
                    'valor_unitario' => $itemData['valor_unitario'],
                    'valor_total' => $valorTotal,
                    'icms_cst' => $itemData['icms_cst'] ?? null,
                    'icms_aliquota' => $itemData['icms_aliquota'] ?? 0,
                    'ipi_cst' => $itemData['ipi_cst'] ?? null,
                    'ipi_aliquota' => $itemData['ipi_aliquota'] ?? 0,
                    'pis_cst' => $itemData['pis_cst'] ?? null,
                    'pis_aliquota' => $itemData['pis_aliquota'] ?? 0,
                    'cofins_cst' => $itemData['cofins_cst'] ?? null,
                    'cofins_aliquota' => $itemData['cofins_aliquota'] ?? 0,
                ]);

                $item->calcularImpostos();
                $item->save();
            }

            $notaFiscal->calcularTotais();
            $notaFiscal->save();

            DB::commit();

            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('success', 'Nota fiscal criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao criar nota fiscal: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(NotaFiscal $notaFiscal)
    {
        $notaFiscal->load(['empresa', 'user', 'itens']);
        return view('notas-fiscais.show', compact('notaFiscal'));
    }

    public function edit(NotaFiscal $notaFiscal)
    {
        if ($notaFiscal->status !== 'rascunho') {
            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('error', 'Apenas notas fiscais em rascunho podem ser editadas.');
        }

        $empresas = Empresa::orderBy('razao_social')->get();
        $notaFiscal->load('itens');

        return view('notas-fiscais.edit', compact('notaFiscal', 'empresas'));
    }

    public function update(Request $request, NotaFiscal $notaFiscal)
    {
        if ($notaFiscal->status !== 'rascunho') {
            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('error', 'Apenas notas fiscais em rascunho podem ser editadas.');
        }

        $validator = Validator::make($request->all(), [
            'empresa_id' => 'required|exists:empresas,id',
            'tipo' => 'required|in:entrada,saida',
            'destinatario_nome' => 'required|string|max:255',
            'destinatario_documento' => 'required|string|max:20',
            'data_emissao' => 'required|date',
            'itens' => 'required|array|min:1',
            'itens.*.codigo_produto' => 'required|string|max:50',
            'itens.*.descricao' => 'required|string|max:255',
            'itens.*.cfop' => 'required|string|size:4',
            'itens.*.quantidade' => 'required|numeric|min:0.0001',
            'itens.*.valor_unitario' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $notaFiscal->update([
                'empresa_id' => $request->empresa_id,
                'tipo' => $request->tipo,
                'destinatario_nome' => $request->destinatario_nome,
                'destinatario_documento' => $request->destinatario_documento,
                'destinatario_endereco' => $request->destinatario_endereco,
                'destinatario_cidade' => $request->destinatario_cidade,
                'destinatario_uf' => $request->destinatario_uf,
                'destinatario_cep' => $request->destinatario_cep,
                'destinatario_telefone' => $request->destinatario_telefone,
                'destinatario_email' => $request->destinatario_email,
                'data_emissao' => $request->data_emissao,
                'data_saida' => $request->data_saida,
                'valor_frete' => $request->valor_frete ?? 0,
                'valor_seguro' => $request->valor_seguro ?? 0,
                'valor_desconto' => $request->valor_desconto ?? 0,
                'valor_outras_despesas' => $request->valor_outras_despesas ?? 0,
                'observacoes' => $request->observacoes,
            ]);

            // Remove itens existentes
            $notaFiscal->itens()->delete();

            // Adiciona novos itens
            foreach ($request->itens as $itemData) {
                $valorTotal = $itemData['quantidade'] * $itemData['valor_unitario'];

                $item = ItemNotaFiscal::create([
                    'nota_fiscal_id' => $notaFiscal->id,
                    'codigo_produto' => $itemData['codigo_produto'],
                    'descricao' => $itemData['descricao'],
                    'ncm' => $itemData['ncm'] ?? null,
                    'cfop' => $itemData['cfop'],
                    'unidade' => $itemData['unidade'] ?? 'UN',
                    'quantidade' => $itemData['quantidade'],
                    'valor_unitario' => $itemData['valor_unitario'],
                    'valor_total' => $valorTotal,
                    'icms_cst' => $itemData['icms_cst'] ?? null,
                    'icms_aliquota' => $itemData['icms_aliquota'] ?? 0,
                    'ipi_cst' => $itemData['ipi_cst'] ?? null,
                    'ipi_aliquota' => $itemData['ipi_aliquota'] ?? 0,
                    'pis_cst' => $itemData['pis_cst'] ?? null,
                    'pis_aliquota' => $itemData['pis_aliquota'] ?? 0,
                    'cofins_cst' => $itemData['cofins_cst'] ?? null,
                    'cofins_aliquota' => $itemData['cofins_aliquota'] ?? 0,
                ]);

                $item->calcularImpostos();
                $item->save();
            }

            $notaFiscal->calcularTotais();
            $notaFiscal->save();

            DB::commit();

            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('success', 'Nota fiscal atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao atualizar nota fiscal: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(NotaFiscal $notaFiscal)
    {
        if ($notaFiscal->status !== 'rascunho') {
            return redirect()->route('notas-fiscais.index')
                ->with('error', 'Apenas notas fiscais em rascunho podem ser excluídas.');
        }

        $notaFiscal->delete();

        return redirect()->route('notas-fiscais.index')
            ->with('success', 'Nota fiscal excluída com sucesso!');
    }

    public function autorizar(NotaFiscal $notaFiscal)
    {
        if ($notaFiscal->status !== 'rascunho') {
            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('error', 'Apenas notas fiscais em rascunho podem ser autorizadas.');
        }

        // Aqui você implementaria a integração com a SEFAZ
        // Por enquanto, vamos simular a autorização
        $notaFiscal->update([
            'status' => 'autorizada',
            'chave_acesso' => $this->gerarChaveAcesso($notaFiscal),
            'protocolo_autorizacao' => 'PROT' . now()->format('YmdHis'),
            'data_autorizacao' => now(),
        ]);

        return redirect()->route('notas-fiscais.show', $notaFiscal)
            ->with('success', 'Nota fiscal autorizada com sucesso!');
    }

    public function cancelar(NotaFiscal $notaFiscal)
    {
        if ($notaFiscal->status !== 'autorizada') {
            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('error', 'Apenas notas fiscais autorizadas podem ser canceladas.');
        }

        // Aqui você implementaria o cancelamento na SEFAZ
        $notaFiscal->update([
            'status' => 'cancelada',
        ]);

        return redirect()->route('notas-fiscais.show', $notaFiscal)
            ->with('success', 'Nota fiscal cancelada com sucesso!');
    }

    public function xml(NotaFiscal $notaFiscal)
    {
        if ($notaFiscal->status !== 'autorizada') {
            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('error', 'Apenas notas fiscais autorizadas possuem XML.');
        }

        // Gerar XML da nota fiscal
        $xml = $this->gerarXML($notaFiscal);

        return response($xml)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="NFe_' . $notaFiscal->chave_acesso . '.xml"');
    }

    public function danfe(NotaFiscal $notaFiscal)
    {
        if ($notaFiscal->status !== 'autorizada') {
            return redirect()->route('notas-fiscais.show', $notaFiscal)
                ->with('error', 'Apenas notas fiscais autorizadas possuem DANFE.');
        }

        // Gerar PDF do DANFE
        return view('notas-fiscais.danfe', compact('notaFiscal'));
    }

    private function gerarChaveAcesso(NotaFiscal $notaFiscal): string
    {
        // Simplificado - em produção, use a lógica oficial da Receita Federal
        $empresa = $notaFiscal->empresa;
        $cnpj = preg_replace('/\D/', '', $empresa->cnpj);
        $dataEmissao = $notaFiscal->data_emissao->format('ymd');
        $serie = str_pad($notaFiscal->serie, 3, '0', STR_PAD_LEFT);
        $numero = str_pad($notaFiscal->numero_nf, 9, '0', STR_PAD_LEFT);

        $chave = $cnpj . $notaFiscal->modelo . $serie . $numero . $dataEmissao . rand(10000000, 99999999);

        return $chave . $this->calcularDV($chave);
    }

    private function calcularDV(string $chave): string
    {
        // Algoritmo simplificado do dígito verificador
        $soma = 0;
        $peso = 2;

        for ($i = strlen($chave) - 1; $i >= 0; $i--) {
            $soma += $chave[$i] * $peso;
            $peso = $peso == 9 ? 2 : $peso + 1;
        }

        $resto = $soma % 11;
        return $resto < 2 ? '0' : (string)(11 - $resto);
    }

    private function gerarXML(NotaFiscal $notaFiscal): string
    {
        // Implementar geração do XML NFe conforme layout da Receita Federal
        // Por enquanto, retorna um XML básico
        return view('notas-fiscais.xml', compact('notaFiscal'))->render();
    }
}
