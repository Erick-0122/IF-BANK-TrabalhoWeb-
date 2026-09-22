<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

/** Logs de auditoria das atividades dos gerentes de conta - gerente geral. */
class AuditController extends Controller
{
    public function __construct(private readonly AuditService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Audit::class);

        $filters = $request->only(['manager_id', 'event', 'from', 'to']);

        return view('gerente-geral.auditoria.index', [
            'audits' => $this->service->managerActivity($filters),
            'managers' => User::where('role', User::ROLE_GERENTE_CONTA)->orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }
}
