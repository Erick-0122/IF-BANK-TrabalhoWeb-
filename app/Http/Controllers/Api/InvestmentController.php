<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvestmentRequest;
use App\Http\Resources\TransactionResource;
use App\Services\BankingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Aplicacoes e resgates em CDB, CDI e Poupanca. */
class InvestmentController extends Controller
{
    public function __construct(private readonly BankingService $banking) {}

    public function index(Request $request): JsonResponse
    {
        $account = $request->user()->account;
        $this->authorize('view', $account);

        return response()->json(
            $this->banking->investments($account)->map(fn ($i) => [
                'type' => $i->type,
                'balance' => (float) $i->balance,
            ])->values()
        );
    }

    public function invest(InvestmentRequest $request): JsonResponse
    {
        $account = $request->user()->account;
        $this->authorize('transact', $account);

        $transaction = $this->banking->invest(
            $account,
            $request->validated('type'),
            (float) $request->validated('amount'),
        );

        return response()->json([
            'message' => 'Aplicação realizada.',
            'transaction' => new TransactionResource($transaction),
            'balance' => (float) $account->fresh()->balance,
        ], 201);
    }

    public function redeem(InvestmentRequest $request): JsonResponse
    {
        $account = $request->user()->account;
        $this->authorize('transact', $account);

        $transaction = $this->banking->redeem(
            $account,
            $request->validated('type'),
            (float) $request->validated('amount'),
        );

        return response()->json([
            'message' => 'Resgate realizado.',
            'transaction' => new TransactionResource($transaction),
            'balance' => (float) $account->fresh()->balance,
        ], 201);
    }
}
