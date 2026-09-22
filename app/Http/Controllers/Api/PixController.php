<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PixRequest;
use App\Http\Resources\TransactionResource;
use App\Services\BankingService;
use Illuminate\Http\JsonResponse;

class PixController extends Controller
{
    public function __construct(private readonly BankingService $banking) {}

    public function store(PixRequest $request): JsonResponse
    {
        $account = $request->user()->account;
        $this->authorize('transact', $account);

        $transaction = $this->banking->sendPix(
            $account,
            $request->validated('pix_key'),
            (float) $request->validated('amount'),
            $request->validated('description'),
        );

        return response()->json([
            'message' => 'Pix enviado.',
            'transaction' => new TransactionResource($transaction),
            'balance' => (float) $account->fresh()->balance,
        ], 201);
    }
}
