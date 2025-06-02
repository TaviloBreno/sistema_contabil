<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'description' => 'Bem-vindo ao painel de controle.',
        ];

        return view('dashboard.index', $data);
    }
}
