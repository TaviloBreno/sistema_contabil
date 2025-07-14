<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotaFiscal;
use Illuminate\Http\Request;

class NotaFiscalApiController extends Controller
{
    public function proximoNumero(Request $request)
    {
        $empresaId = $request->get('empresa_id');
        $serie = $request->get('serie', '001');

        if (!$empresaId) {
            return response()->json(['error' => 'Empresa é obrigatória'], 400);
        }

        $proximoNumero = NotaFiscal::gerarProximoNumero($empresaId, $serie);

        return response()->json([
            'proximo_numero' => $proximoNumero
        ]);
    }

    public function consultarStatus(NotaFiscal $notaFiscal)
    {
        return response()->json([
            'status' => $notaFiscal->status,
            'chave_acesso' => $notaFiscal->chave_acesso,
            'protocolo_autorizacao' => $notaFiscal->protocolo_autorizacao,
            'data_autorizacao' => $notaFiscal->data_autorizacao?->format('d/m/Y H:i:s'),
            'motivo_rejeicao' => $notaFiscal->motivo_rejeicao
        ]);
    }

    public function estatisticas(Request $request)
    {
        $empresaId = $request->get('empresa_id');
        $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', now()->endOfMonth()->format('Y-m-d'));

        $query = NotaFiscal::query()
            ->whereDate('data_emissao', '>=', $dataInicio)
            ->whereDate('data_emissao', '<=', $dataFim);

        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }

        $estatisticas = [
            'total_notas' => $query->count(),
            'total_valor' => $query->sum('valor_total'),
            'notas_autorizadas' => $query->where('status', 'autorizada')->count(),
            'notas_rascunho' => $query->where('status', 'rascunho')->count(),
            'notas_canceladas' => $query->where('status', 'cancelada')->count(),
            'notas_rejeitadas' => $query->where('status', 'rejeitada')->count(),
            'valor_icms' => $query->sum('valor_icms'),
            'valor_ipi' => $query->sum('valor_ipi'),
            'notas_saida' => $query->where('tipo', 'saida')->count(),
            'notas_entrada' => $query->where('tipo', 'entrada')->count()
        ];

        return response()->json($estatisticas);
    }
}
