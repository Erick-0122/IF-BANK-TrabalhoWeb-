<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountRepository implements AccountRepositoryInterface
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return Account::with(['user', 'manager'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?Account
    {
        return Account::with(['user', 'manager', 'investments'])->find($id);
    }

    public function findByUser(int $userId): ?Account
    {
        return Account::with('investments')->where('user_id', $userId)->first();
    }

    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function update(Account $account, array $data): Account
    {
        $account->update($data);

        return $account->fresh();
    }

    public function delete(Account $account): void
    {
        $account->delete();
    }

    public function addToBalance(Account $account, float $amount): Account
    {
        $account->balance = (float) $account->balance + $amount;
        $account->save();

        return $account;
    }
}
