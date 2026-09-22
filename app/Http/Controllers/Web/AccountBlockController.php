<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\ClientAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Bloqueio/desbloqueio por suspeita de fraude - gerente de conta. */
class AccountBlockController extends Controller
{
    public function __construct(private readonly ClientAccountService $service) {}

    public function block(Request $request, Account $conta): RedirectResponse
    {
        $this->authorize('block', $conta);

        $data = $request->validate(['block_reason' => ['required', 'string', 'max:255']]);

        $this->service->block($conta, $data['block_reason']);

        return back()->with('status', "Conta {$conta->number} bloqueada.");
    }

    public function unblock(Account $conta): RedirectResponse
    {
        $this->authorize('block', $conta);

        $this->service->unblock($conta);

        return back()->with('status', "Conta {$conta->number} desbloqueada.");
    }
}
