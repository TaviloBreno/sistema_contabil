<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Obrigacao;
use App\Models\NotaFiscal;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas de notas fiscais do mês atual
        $notasFiscaisDoMes = NotaFiscal::whereYear('data_emissao', now()->year)
            ->whereMonth('data_emissao', now()->month)
            ->get();

        $data = [
            'title' => 'Dashboard',
            'description' => 'Bem-vindo ao painel de controle.',
            'numberOfCompaniesRegistered' => Empresa::count(),
            'totalObligations' => Obrigacao::count(),
            'totalRegistredUsers' => User::count(),
            'totalNotasFiscais' => NotaFiscal::count(),
            'notasFiscaisDoMes' => $notasFiscaisDoMes->count(),
            'valorTotalDoMes' => $notasFiscaisDoMes->sum('valor_total'),
            'notasAutorizadas' => $notasFiscaisDoMes->where('status', 'autorizada')->count(),
            'notasRascunho' => $notasFiscaisDoMes->where('status', 'rascunho')->count(),
        ];

        return view('dashboard.index', $data);
    }
}
