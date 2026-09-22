<?php

namespace App\Repositories\Eloquent;

use App\Models\LimitRequest;
use App\Repositories\Contracts\LimitRequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LimitRequestRepository implements LimitRequestRepositoryInterface
{
    public function paginate(?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return LimitRequest::with(['account.user', 'requester', 'reviewer'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?LimitRequest
    {
        return LimitRequest::with(['account.user', 'requester'])->find($id);
    }

    public function create(array $data): LimitRequest
    {
        return LimitRequest::create($data);
    }

    public function update(LimitRequest $request, array $data): LimitRequest
    {
        $request->update($data);

        return $request->fresh();
    }

    public function hasPendingForAccount(int $accountId): bool
    {
        return LimitRequest::where('account_id', $accountId)
            ->where('status', LimitRequest::PENDENTE)
            ->exists();
    }
}
