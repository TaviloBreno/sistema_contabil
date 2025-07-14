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
use App\Http\Controllers\NotaFiscalController;

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
    Route::resource('obrigacoes', ObrigacaoController::class)->parameters([
        'obrigacoes' => 'obrigacao'
    ]);


    // Usuários do sistema
    Route::resource('usuarios', UserController::class);

    // Documentos
    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/download/{documento}', [DocumentoController::class, 'download'])->name('documentos.download');

    // Relatórios
    Route::get('relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('relatorios/exportar', [RelatorioController::class, 'exportarPdf'])->name('relatorios.exportarPdf');
    Route::get('/relatorios/grafico', [RelatorioController::class, 'grafico'])->name('relatorios.grafico');

    // Configurações do sistema
    Route::get('/configuracoes', [ConfiguracaoSistemaController::class, 'index'])->name('configuracoes.index');
    Route::post('/configuracoes', [ConfiguracaoSistemaController::class, 'store'])->name('configuracoes.store');

    // Consultar CNPJ
    Route::get('/consulta-cnpj', [EmpresaController::class, 'consultarCNPJ'])->name('empresas.consultarCNPJ');

    Route::get('/relatorios/grafico', [RelatorioController::class, 'grafico'])->name('relatorios.grafico');

    // Notas Fiscais
    Route::resource('notas-fiscais', NotaFiscalController::class);
    Route::post('/notas-fiscais/{notaFiscal}/autorizar', [NotaFiscalController::class, 'autorizar'])->name('notas-fiscais.autorizar');
    Route::post('/notas-fiscais/{notaFiscal}/cancelar', [NotaFiscalController::class, 'cancelar'])->name('notas-fiscais.cancelar');
    Route::get('/notas-fiscais/{notaFiscal}/xml', [NotaFiscalController::class, 'xml'])->name('notas-fiscais.xml');
    Route::get('/notas-fiscais/{notaFiscal}/danfe', [NotaFiscalController::class, 'danfe'])->name('notas-fiscais.danfe');

    // API para Notas Fiscais
    Route::prefix('api')->group(function () {
        Route::get('/notas-fiscais/proximo-numero', [App\Http\Controllers\Api\NotaFiscalApiController::class, 'proximoNumero'])->name('api.notas-fiscais.proximo-numero');
        Route::get('/notas-fiscais/{notaFiscal}/status', [App\Http\Controllers\Api\NotaFiscalApiController::class, 'consultarStatus'])->name('api.notas-fiscais.status');
        Route::get('/notas-fiscais/estatisticas', [App\Http\Controllers\Api\NotaFiscalApiController::class, 'estatisticas'])->name('api.notas-fiscais.estatisticas');
    });
});
