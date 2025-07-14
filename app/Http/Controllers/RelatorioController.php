<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obrigacao;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public $obrigacao;

    public function __construct(Obrigacao $obrigacao)
    {
        $this->obrigacao = $obrigacao;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $obrigacoes = $this->obrigacao->with('empresa')
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->filled('data_inicio') && $request->filled('data_fim'), function ($query) use ($request) {
                return $query->whereBetween('data_vencimento', [
                    Carbon::parse($request->data_inicio),
                    Carbon::parse($request->data_fim)
                ]);
            })
            ->latest()
            ->paginate(10);

        $graficoUrl = route('relatorios.grafico');

        return view('relatorios.index', compact('obrigacoes', 'graficoUrl'));
    }

    public function exportarPdf(Request $request)
    {
        $obrigacoes = $this->obrigacao->with('empresa')
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->filled('data_inicio') && $request->filled('data_fim'), function ($query) use ($request) {
                return $query->whereBetween('data_vencimento', [
                    Carbon::parse($request->data_inicio),
                    Carbon::parse($request->data_fim)
                ]);
            })
            ->get();

        $pendentes = $obrigacoes->where('status', 'pendente')->count();
        $emAndamento = $obrigacoes->where('status', 'em andamento')->count();
        $concluidas = $obrigacoes->where('status', 'concluida')->count();

        // Gerar URL do gráfico
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => ['Pendente', 'Em Andamento', 'Concluída'],
                'datasets' => [[
                    'label' => 'Total de Obrigações',
                    'data' => [$pendentes, $emAndamento, $concluidas],
                    'backgroundColor' => ['#f39c12', '#3498db', '#2ecc71'],
                ]]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Gráfico de Obrigações por Status'
                ]
            ]
        ];

        $graficoUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode($chartConfig));

        // Gerar PDF
        $pdf = Pdf::loadView('relatorios.pdf', [
            'obrigacoes' => $obrigacoes,
            'pendentes' => $pendentes,
            'emAndamento' => $emAndamento,
            'concluidas' => $concluidas,
            'graficoUrl' => $graficoUrl,
        ]);

        return $pdf->download('relatorio_obrigacoes.pdf');
    }

    public function grafico()
    {
        // Gerar dados do gráfico (exemplo simples)
        return response()->json([
            'labels' => ['Pendente', 'Em Andamento', 'Concluída'],
            'data' => [10, 5, 7]
        ]);
    }

    public function dashboard()
    {
        $dadosGerais = [
            'empresas_ativas' => \App\Models\Empresa::where('status', 'ativa')->count(),
            'obrigacoes_pendentes' => \App\Models\Obrigacao::where('status', 'pendente')->count(),
            'notas_fiscais_mes' => \App\Models\NotaFiscal::whereMonth('data_emissao', now()->month)->count(),
            'documentos_upload' => \App\Models\DocumentoUpload::count(),
        ];

        $receitasPorMes = \App\Models\NotaFiscal::selectRaw('MONTH(data_emissao) as mes, SUM(valor_total) as total')
            ->whereYear('data_emissao', now()->year)
            ->where('status', 'autorizada')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $impostosPorMes = \App\Models\NotaFiscal::selectRaw('MONTH(data_emissao) as mes, SUM(valor_icms + valor_ipi + valor_pis + valor_cofins) as total')
            ->whereYear('data_emissao', now()->year)
            ->where('status', 'autorizada')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $obrigacoesVencendo = \App\Models\Obrigacao::with('empresa')
            ->where('data_vencimento', '>=', now())
            ->where('data_vencimento', '<=', now()->addDays(30))
            ->where('status', 'pendente')
            ->orderBy('data_vencimento')
            ->limit(10)
            ->get();

        $empresasComMaisObrigacoes = \App\Models\Empresa::withCount(['obrigacoes' => function ($query) {
            $query->where('status', 'pendente');
        }])
            ->having('obrigacoes_count', '>', 0)
            ->orderBy('obrigacoes_count', 'desc')
            ->limit(5)
            ->get();

        return view('relatorios.dashboard', compact(
            'dadosGerais',
            'receitasPorMes',
            'impostosPorMes',
            'obrigacoesVencendo',
            'empresasComMaisObrigacoes'
        ));
    }

    public function obrigacoesPendentes()
    {
        $obrigacoes = \App\Models\Obrigacao::with('empresa')
            ->where('status', 'pendente')
            ->orderBy('data_vencimento')
            ->get();

        return view('relatorios.obrigacoes-pendentes', compact('obrigacoes'));
    }

    public function notasFiscaisPorPeriodo(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'empresa_id' => 'nullable|exists:empresas,id',
            'status' => 'nullable|in:rascunho,autorizada,cancelada,rejeitada'
        ]);

        $query = \App\Models\NotaFiscal::with('empresa')
            ->whereBetween('data_emissao', [$request->data_inicio, $request->data_fim]);

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notasFiscais = $query->orderBy('data_emissao', 'desc')->get();

        $totais = [
            'quantidade' => $notasFiscais->count(),
            'valor_total' => $notasFiscais->sum('valor_total'),
            'valor_icms' => $notasFiscais->sum('valor_icms'),
            'valor_ipi' => $notasFiscais->sum('valor_ipi'),
            'valor_pis' => $notasFiscais->sum('valor_pis'),
            'valor_cofins' => $notasFiscais->sum('valor_cofins'),
        ];

        return view('relatorios.notas-fiscais-periodo', compact('notasFiscais', 'totais'));
    }

    public function documentosPorCategoria()
    {
        $documentosPorCategoria = \App\Models\DocumentoUpload::selectRaw('categoria, COUNT(*) as quantidade, SUM(tamanho) as tamanho_total')
            ->groupBy('categoria')
            ->get();

        $documentosRecentes = \App\Models\DocumentoUpload::with(['empresa', 'user'])
            ->latest()
            ->limit(20)
            ->get();

        return view('relatorios.documentos-categoria', compact('documentosPorCategoria', 'documentosRecentes'));
    }

    public function exportarExcel(Request $request)
    {
        $tipo = $request->get('tipo', 'obrigacoes');

        switch ($tipo) {
            case 'obrigacoes':
                return $this->exportarObrigacoesExcel($request);
            case 'notas_fiscais':
                return $this->exportarNotasFiscaisExcel($request);
            case 'documentos':
                return $this->exportarDocumentosExcel($request);
            default:
                return redirect()->back()->with('error', 'Tipo de relatório inválido');
        }
    }

    private function exportarObrigacoesExcel($request)
    {
        $obrigacoes = \App\Models\Obrigacao::with('empresa')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="obrigacoes.xls"',
        ];

        $table = '<table border="1">';
        $table .= '<tr><th>Empresa</th><th>Tipo</th><th>Data Vencimento</th><th>Status</th><th>Valor</th></tr>';

        foreach ($obrigacoes as $obrigacao) {
            $table .= '<tr>';
            $table .= '<td>' . $obrigacao->empresa->razao_social . '</td>';
            $table .= '<td>' . $obrigacao->tipo . '</td>';
            $table .= '<td>' . $obrigacao->data_vencimento->format('d/m/Y') . '</td>';
            $table .= '<td>' . $obrigacao->status . '</td>';
            $table .= '<td>R$ ' . number_format($obrigacao->valor ?? 0, 2, ',', '.') . '</td>';
            $table .= '</tr>';
        }

        $table .= '</table>';

        return response($table, 200, $headers);
    }

    private function exportarNotasFiscaisExcel($request)
    {
        $notasFiscais = \App\Models\NotaFiscal::with('empresa')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="notas_fiscais.xls"',
        ];

        $table = '<table border="1">';
        $table .= '<tr><th>Empresa</th><th>Número</th><th>Série</th><th>Data Emissão</th><th>Status</th><th>Valor Total</th></tr>';

        foreach ($notasFiscais as $nota) {
            $table .= '<tr>';
            $table .= '<td>' . $nota->empresa->razao_social . '</td>';
            $table .= '<td>' . $nota->numero_nf . '</td>';
            $table .= '<td>' . $nota->serie . '</td>';
            $table .= '<td>' . $nota->data_emissao->format('d/m/Y') . '</td>';
            $table .= '<td>' . $nota->status . '</td>';
            $table .= '<td>R$ ' . number_format($nota->valor_total, 2, ',', '.') . '</td>';
            $table .= '</tr>';
        }

        $table .= '</table>';

        return response($table, 200, $headers);
    }

    private function exportarDocumentosExcel($request)
    {
        $documentos = \App\Models\DocumentoUpload::with(['empresa', 'user'])->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="documentos.xls"',
        ];

        $table = '<table border="1">';
        $table .= '<tr><th>Empresa</th><th>Arquivo</th><th>Categoria</th><th>Tamanho</th><th>Usuário</th><th>Data Upload</th></tr>';

        foreach ($documentos as $doc) {
            $table .= '<tr>';
            $table .= '<td>' . $doc->empresa->razao_social . '</td>';
            $table .= '<td>' . $doc->nome_original . '</td>';
            $table .= '<td>' . $doc->categoria_descricao . '</td>';
            $table .= '<td>' . $doc->tamanho_formatado . '</td>';
            $table .= '<td>' . $doc->user->name . '</td>';
            $table .= '<td>' . $doc->created_at->format('d/m/Y H:i') . '</td>';
            $table .= '</tr>';
        }

        $table .= '</table>';

        return response($table, 200, $headers);
    }
}
