<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LimitRequest;
use App\Services\LimitRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Aprovacao/reprovacao das solicitacoes de aumento de limite - gerente geral. */
class LimitApprovalController extends Controller
{
    public function __construct(private readonly LimitRequestService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', LimitRequest::class);

        return view('gerente-geral.limites.index', [
            'requests' => $this->service->list($request->query('status')),
            'status' => $request->query('status'),
        ]);
    }

    public function approve(Request $request, LimitRequest $solicitacao): RedirectResponse
    {
        $this->authorize('review', $solicitacao);

        $this->service->approve($solicitacao, $request->user());

        return back()->with('status', 'Solicitação aprovada e limite atualizado.');
    }

    public function reject(Request $request, LimitRequest $solicitacao): RedirectResponse
    {
        $this->authorize('review', $solicitacao);

        $this->service->reject($solicitacao, $request->user());

        return back()->with('status', 'Solicitação reprovada.');
    }
}
