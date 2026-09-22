<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\PixController;
use App\Http\Controllers\Api\StatementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas API (guard "sanctum" - token) consumidas pelo SPA Svelte do cliente
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware(['auth:sanctum', 'role:cliente'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Saldo em tempo real
    Route::get('/saldo', BalanceController::class);

    // Extrato por periodo: /api/extrato?from=2026-01-01&to=2026-01-31
    Route::get('/extrato', StatementController::class);

    // Movimentacoes
    Route::post('/pix', [PixController::class, 'store']);
    Route::get('/investimentos', [InvestmentController::class, 'index']);
    Route::post('/investimentos/aplicar', [InvestmentController::class, 'invest']);
    Route::post('/investimentos/resgatar', [InvestmentController::class, 'redeem']);
});
