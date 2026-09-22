<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientAccountRequest;
use App\Http\Requests\UpdateClientAccountRequest;
use App\Models\Account;
use App\Services\ClientAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** CRUD das contas de clientes - acesso do gerente de conta. */
class ClientAccountController extends Controller
{
    public function __construct(private readonly ClientAccountService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Account::class);

        return view('gerente-conta.clientes.index', [
            'accounts' => $this->service->list(10, $request->query('busca')),
            'search' => $request->query('busca'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Account::class);

        return view('gerente-conta.clientes.form', ['account' => new Account()]);
    }

    public function store(StoreClientAccountRequest $request): RedirectResponse
    {
        $this->authorize('create', Account::class);

        $account = $this->service->create($request->validated(), $request->user());

        return redirect()->route('contas.index')
            ->with('status', "Conta {$account->number} criada. Credenciais enviadas por e-mail.");
    }

    public function show(Account $conta)
    {
        $this->authorize('view', $conta);

        return view('gerente-conta.clientes.show', ['account' => $conta->load(['investments', 'limitRequests'])]);
    }

    public function edit(Account $conta)
    {
        $this->authorize('update', $conta);

        return view('gerente-conta.clientes.form', ['account' => $conta->load('user')]);
    }

    public function update(UpdateClientAccountRequest $request, Account $conta): RedirectResponse
    {
        $this->authorize('update', $conta);

        $this->service->update($conta, $request->validated());

        return redirect()->route('contas.index')->with('status', 'Conta atualizada.');
    }

    public function destroy(Account $conta): RedirectResponse
    {
        $this->authorize('delete', $conta);

        $this->service->delete($conta);

        return redirect()->route('contas.index')->with('status', 'Conta removida.');
    }
}
