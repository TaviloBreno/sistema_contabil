<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Obrigacao;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct(Empresa $empresa, Obrigacao $obrigacao)
    {
        $this->empresa = $empresa;
        $this->obrigacao = $obrigacao;
    }
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'description' => 'Bem-vindo ao painel de controle.',
            'numberOfCompaniesRegistered' => $this->empresa->count(),
            'totalObligations' => $this->obrigacao->count(),
            'totalRegistredUsers' => User::count(),
        ];

        return view('dashboard.index', $data);
    }
}
