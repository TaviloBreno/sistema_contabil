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
}
