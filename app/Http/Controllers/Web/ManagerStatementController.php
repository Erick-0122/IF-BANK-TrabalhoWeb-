<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatementRequest;
use App\Models\Account;
use App\Services\StatementService;

/** Extrato do cliente visto pelo gerente de conta. */
class ManagerStatementController extends Controller
{
    public function __construct(private readonly StatementService $service) {}

    public function show(StatementRequest $request, Account $conta)
    {
        $this->authorize('viewStatement', $conta);

        $statement = $this->service->generate(
            $conta,
            $request->validated('from'),
            $request->validated('to'),
        );

        return view('gerente-conta.extrato.show', $statement);
    }
}
