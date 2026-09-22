<?php

namespace App\Repositories\Contracts;

use App\Models\LimitRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LimitRequestRepositoryInterface
{
    public function paginate(?string $status = null, int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): ?LimitRequest;

    public function create(array $data): LimitRequest;

    public function update(LimitRequest $request, array $data): LimitRequest;

    public function hasPendingForAccount(int $accountId): bool;
}
