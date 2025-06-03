<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\User;

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
            'totalRegistredUsers' => User::count(),
        ];

        return view('dashboard.index', $data);
    }
}
