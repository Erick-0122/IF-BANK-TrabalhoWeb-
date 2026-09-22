<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLimitRequestRequest;
use App\Models\Account;
use App\Models\LimitRequest;
use App\Services\LimitRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Solicitacao de aumento de limite feita pelo gerente de conta. */
class LimitRequestController extends Controller
{
    public function __construct(private readonly LimitRequestService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', LimitRequest::class);

        return view('gerente-conta.limites', ['requests' => $this->service->list()]);
    }

    public function store(StoreLimitRequestRequest $request, Account $conta): RedirectResponse
    {
        $this->authorize('create', LimitRequest::class);

        $this->service->request(
            $conta,
            $request->user(),
            (float) $request->validated('requested_limit'),
            $request->validated('justification'),
        );

        return back()->with('status', 'Solicitação enviada ao gerente geral.');
    }
}
