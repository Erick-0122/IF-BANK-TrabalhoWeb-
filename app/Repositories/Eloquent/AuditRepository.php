<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\AuditRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OwenIt\Auditing\Models\Audit;

class AuditRepository implements AuditRepositoryInterface
{
    public function paginateManagerActivity(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $managerIds = User::where('role', User::ROLE_GERENTE_CONTA)->pluck('id');

        return Audit::with('user')
            ->whereIn('user_id', $managerIds)
            ->when($filters['manager_id'] ?? null, fn ($q, $id) => $q->where('user_id', $id))
            ->when($filters['event'] ?? null, fn ($q, $e) => $q->where('event', $e))
            ->when($filters['from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($filters['to'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }
}
