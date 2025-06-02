<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'description' => 'Bem-vindo ao painel de controle.',
            'numberOfCompaniesRegistered' => $this->empresa->count(),
        ];

        return view('dashboard.index', $data);
    }
}
