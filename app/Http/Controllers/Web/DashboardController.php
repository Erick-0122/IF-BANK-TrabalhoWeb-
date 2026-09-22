<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LimitRequest;
use App\Models\User;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, AccountRepositoryInterface $accounts)
    {
        $user = $request->user();

        if ($user->isGerenteGeral()) {
            return view('gerente-geral.dashboard', [
                'totalManagers' => User::where('role', User::ROLE_GERENTE_CONTA)->count(),
                'pendingRequests' => LimitRequest::where('status', LimitRequest::PENDENTE)->count(),
            ]);
        }

        if ($user->isGerenteConta()) {
            return view('gerente-conta.dashboard', [
                'accounts' => $accounts->paginate(5),
            ]);
        }

        // Cliente usa o SPA Svelte; o monolito nao tem area para ele.
        abort(403, 'Clientes devem acessar o aplicativo IFBank.');
    }
}
