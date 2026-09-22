<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatementRequest;
use App\Http\Resources\TransactionResource;
use App\Services\StatementService;
use Illuminate\Http\JsonResponse;

/** Extrato por periodo do proprio cliente. */
class StatementController extends Controller
{
    public function __construct(private readonly StatementService $service) {}

    public function __invoke(StatementRequest $request): JsonResponse
    {
        $account = $request->user()->account;
        $this->authorize('viewStatement', $account);

        $statement = $this->service->generate(
            $account,
            $request->validated('from'),
            $request->validated('to'),
            enforceBlock: true,
        );

        return response()->json([
            'from' => $statement['from'],
            'to' => $statement['to'],
            'credits' => $statement['credits'],
            'debits' => $statement['debits'],
            'balance' => $statement['balance'],
            'transactions' => TransactionResource::collection($statement['transactions']),
        ]);
    }
}
