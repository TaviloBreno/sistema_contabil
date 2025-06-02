<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObrigacaoController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::resource('empresas', EmpresaController::class);

Route::resource('obrigacoes', ObrigacaoController::class);
