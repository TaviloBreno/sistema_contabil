<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
use App\Http\Controllers\DocumentoUploadController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\IntegracaoController;
use App\Http\Controllers\ChatController;

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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Empresas
    Route::resource('empresas', EmpresaController::class);
    Route::get('/consulta-cnpj', [EmpresaController::class, 'consultarCNPJ'])->name('empresas.consultarCNPJ');

    // Obrigações
    Route::resource('obrigacoes', ObrigacaoController::class)->parameters([
        'obrigacoes' => 'obrigacao'
    ]);

    // Usuários
    Route::resource('usuarios', UserController::class);

    // Documentos simples
    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/download/{documento}', [DocumentoController::class, 'download'])->name('documentos.download');

    // Upload de documentos com vínculo
    Route::resource('documentos', DocumentoUploadController::class);
    Route::get('documentos/{documento}/download', [DocumentoUploadController::class, 'download'])->name('documentos.download');
    Route::post('documentos/{documento}/vincular-obrigacao', [DocumentoUploadController::class, 'vincularObrigacao'])->name('documentos.vincular-obrigacao');
    Route::post('documentos/{documento}/vincular-nota-fiscal', [DocumentoUploadController::class, 'vincularNotaFiscal'])->name('documentos.vincular-nota-fiscal');

    // Notas Fiscais
    Route::resource('notas-fiscais', NotaFiscalController::class);
    Route::post('/notas-fiscais/{notaFiscal}/autorizar', [NotaFiscalController::class, 'autorizar'])->name('notas-fiscais.autorizar');
    Route::post('/notas-fiscais/{notaFiscal}/cancelar', [NotaFiscalController::class, 'cancelar'])->name('notas-fiscais.cancelar');
    Route::get('/notas-fiscais/{notaFiscal}/xml', [NotaFiscalController::class, 'xml'])->name('notas-fiscais.xml');
    Route::get('/notas-fiscais/{notaFiscal}/danfe', [NotaFiscalController::class, 'danfe'])->name('notas-fiscais.danfe');

    // Chat
    Route::resource('chat', ChatController::class);
    Route::post('/chat/{chat}/send-message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
    Route::get('/chat/{chat}/messages', [ChatController::class, 'getMessages'])->name('chat.get-messages');
    Route::post('/chat/{chat}/mark-as-read', [ChatController::class, 'markAsRead'])->name('chat.mark-as-read');
    Route::post('/chat/{chat}/leave', [ChatController::class, 'leave'])->name('chat.leave');
    Route::post('/chat/private/{user}', [ChatController::class, 'getPrivateChat'])->name('chat.private');

    // Relatórios
    Route::get('relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('relatorios/exportar', [RelatorioController::class, 'exportarPdf'])->name('relatorios.exportarPdf');
    Route::get('relatorios/grafico', [RelatorioController::class, 'grafico'])->name('relatorios.grafico');

    // Relatórios Avançados
    Route::get('relatorios/dashboard', [RelatorioController::class, 'dashboard'])->name('relatorios.dashboard');
    Route::get('relatorios/obrigacoes-pendentes', [RelatorioController::class, 'obrigacoesPendentes'])->name('relatorios.obrigacoes-pendentes');
    Route::get('relatorios/notas-fiscais-periodo', [RelatorioController::class, 'notasFiscaisPorPeriodo'])->name('relatorios.notas-fiscais-periodo');
    Route::get('relatorios/documentos-categoria', [RelatorioController::class, 'documentosPorCategoria'])->name('relatorios.documentos-categoria');
    Route::get('relatorios/exportar-excel', [RelatorioController::class, 'exportarExcel'])->name('relatorios.exportar-excel');

    // Configurações do Sistema
    Route::get('/configuracoes', [ConfiguracaoSistemaController::class, 'index'])->name('configuracoes.index');
    Route::post('/configuracoes', [ConfiguracaoSistemaController::class, 'store'])->name('configuracoes.store');

    // Logs de Atividade
    Route::get('logs', [LogActivityController::class, 'index'])->name('logs.index');
    Route::get('logs/{activity}', [LogActivityController::class, 'show'])->name('logs.show');
    Route::delete('logs/clear', [LogActivityController::class, 'clear'])->name('logs.clear');

    // Integrações com APIs externas
    Route::post('integracoes/consultar-cnpj', [IntegracaoController::class, 'consultarCnpj'])->name('integracoes.consultar-cnpj');
    Route::post('integracoes/consultar-cep', [IntegracaoController::class, 'consultarCep'])->name('integracoes.consultar-cep');
    Route::post('integracoes/consultar-nfe', [IntegracaoController::class, 'consultarNfe'])->name('integracoes.consultar-nfe');
    Route::get('integracoes/consultar-cotacoes', [IntegracaoController::class, 'consultarCotacoes'])->name('integracoes.consultar-cotacoes');

    /*
    |--------------------------------------------------------------------------
    | APIs internas para AJAX
    |--------------------------------------------------------------------------
    */
    Route::prefix('api')->group(function () {
        Route::get('obrigacoes', function (Request $request) {
            $query = \App\Models\Obrigacao::query();
            if ($request->filled('empresa_id')) {
                $query->where('empresa_id', $request->empresa_id);
            }
            return $query->get(['id', 'tipo as nome', 'data_vencimento as vencimento']);
        });

        Route::get('notas-fiscais', function (Request $request) {
            $query = \App\Models\NotaFiscal::query();
            if ($request->filled('empresa_id')) {
                $query->where('empresa_id', $request->empresa_id);
            }
            return $query->get(['id', 'numero_nf', 'destinatario_nome']);
        });

        // APIs RESTful para Notas Fiscais
        Route::get('/notas-fiscais/proximo-numero', [App\Http\Controllers\Api\NotaFiscalApiController::class, 'proximoNumero'])->name('api.notas-fiscais.proximo-numero');
        Route::get('/notas-fiscais/{notaFiscal}/status', [App\Http\Controllers\Api\NotaFiscalApiController::class, 'consultarStatus'])->name('api.notas-fiscais.status');
        Route::get('/notas-fiscais/estatisticas', [App\Http\Controllers\Api\NotaFiscalApiController::class, 'estatisticas'])->name('api.notas-fiscais.estatisticas');
    });
});
