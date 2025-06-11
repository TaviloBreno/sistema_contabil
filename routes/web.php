<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ObrigacaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ConfiguracaoSistemaController;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');


/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // Empresas
    Route::resource('empresas', EmpresaController::class);

    // Obrigações
    Route::resource('obrigacoes', ObrigacaoController::class);

    // Usuários do sistema
    Route::resource('usuarios', UserController::class);

    // Documentos
    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/download/{documento}', [DocumentoController::class, 'download'])->name('documentos.download');

    // Relatórios
    Route::get('relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('relatorios/exportar', [RelatorioController::class, 'exportarPdf'])->name('relatorios.exportarPdf');

    // Configurações do sistema
    Route::get('/configuracoes', [ConfiguracaoSistemaController::class, 'index'])->name('configuracoes.index');
    Route::post('/configuracoes', [ConfiguracaoSistemaController::class, 'store'])->name('configuracoes.store');
});
