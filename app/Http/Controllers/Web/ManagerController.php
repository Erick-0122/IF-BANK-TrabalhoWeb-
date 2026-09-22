<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Models\User;
use App\Services\ManagerService;
use Illuminate\Http\RedirectResponse;

/** CRUD de Gerentes de Conta - acesso exclusivo do gerente geral. */
class ManagerController extends Controller
{
    public function __construct(private readonly ManagerService $service) {}

    public function index()
    {
        $this->authorize('viewAny', User::class);

        return view('gerente-geral.gerentes.index', ['managers' => $this->service->list()]);
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('gerente-geral.gerentes.form', ['manager' => new User()]);
    }

    public function store(StoreManagerRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $this->service->create($request->validated());

        return redirect()->route('gerentes.index')
            ->with('status', 'Gerente cadastrado. As credenciais foram enviadas por e-mail.');
    }

    public function edit(User $gerente)
    {
        $this->authorize('update', $gerente);

        return view('gerente-geral.gerentes.form', ['manager' => $gerente]);
    }

    public function update(UpdateManagerRequest $request, User $gerente): RedirectResponse
    {
        $this->authorize('update', $gerente);

        $this->service->update($gerente, $request->validated());

        return redirect()->route('gerentes.index')->with('status', 'Dados do gerente atualizados.');
    }

    public function destroy(User $gerente): RedirectResponse
    {
        $this->authorize('delete', $gerente);

        $this->service->delete($gerente);

        return redirect()->route('gerentes.index')->with('status', 'Gerente removido.');
    }
}
