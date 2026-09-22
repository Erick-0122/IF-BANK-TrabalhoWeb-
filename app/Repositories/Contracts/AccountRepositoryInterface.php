<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountRepositoryInterface
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator;

    public function find(int $id): ?Account;

    public function findByUser(int $userId): ?Account;

    public function create(array $data): Account;

    public function update(Account $account, array $data): Account;

    public function delete(Account $account): void;

    public function addToBalance(Account $account, float $amount): Account;
}
