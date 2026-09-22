<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BankingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Saldo em tempo real + posicao dos investimentos (tela inicial do cliente). */
class BalanceController extends Controller
{
    public function __construct(private readonly BankingService $banking) {}

    public function __invoke(Request $request): JsonResponse
    {
        $account = $request->user()->account;
        $this->authorize('view', $account);

        $investments = $this->banking->investments($account);

        return response()->json([
            'account_number' => $account->number,
            'balance' => (float) $account->balance,
            'limit' => (float) $account->limit,
            'available' => $account->availableBalance(),
            'blocked' => $account->blocked,
            'block_reason' => $account->block_reason,
            'investments' => $investments->map(fn ($i) => [
                'type' => $i->type,
                'balance' => (float) $i->balance,
            ])->values(),
            'invested_total' => (float) $investments->sum('balance'),
        ]);
    }
}
