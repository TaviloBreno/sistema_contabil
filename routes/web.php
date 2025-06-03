<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ObrigacaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentoController;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


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
});
