<?php

use App\Http\Controllers\Web\AccountBlockController;
use App\Http\Controllers\Web\AuditController;
use App\Http\Controllers\Web\ClientAccountController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LimitApprovalController;
use App\Http\Controllers\Web\LimitRequestController;
use App\Http\Controllers\Web\ManagerController;
use App\Http\Controllers\Web\ManagerStatementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Web (guard "web" - sessao/cookie, autenticacao via Breeze)
|--------------------------------------------------------------------------
| Area interna da agencia: gerente geral e gerente de conta.
*/

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // ----- Gerente Geral -----
    Route::middleware('role:gerente_geral')->group(function () {
        Route::resource('gerentes', ManagerController::class)
            ->parameters(['gerentes' => 'gerente'])
            ->except('show');

        Route::get('solicitacoes', [LimitApprovalController::class, 'index'])->name('solicitacoes.index');
        Route::patch('solicitacoes/{solicitacao}/aprovar', [LimitApprovalController::class, 'approve'])->name('solicitacoes.aprovar');
        Route::patch('solicitacoes/{solicitacao}/reprovar', [LimitApprovalController::class, 'reject'])->name('solicitacoes.reprovar');

        Route::get('auditoria', [AuditController::class, 'index'])->name('auditoria.index');
    });

    // ----- Gerente de Conta -----
    Route::middleware('role:gerente_conta')->group(function () {
        Route::resource('contas', ClientAccountController::class)->parameters(['contas' => 'conta']);

        Route::get('contas/{conta}/extrato', [ManagerStatementController::class, 'show'])->name('contas.extrato');

        Route::patch('contas/{conta}/bloquear', [AccountBlockController::class, 'block'])->name('contas.bloquear');
        Route::patch('contas/{conta}/desbloquear', [AccountBlockController::class, 'unblock'])->name('contas.desbloquear');

        Route::get('limites', [LimitRequestController::class, 'index'])->name('limites.index');
        Route::post('contas/{conta}/limite', [LimitRequestController::class, 'store'])->name('contas.limite');
    });
});

require __DIR__.'/auth.php'; // rotas do Breeze
