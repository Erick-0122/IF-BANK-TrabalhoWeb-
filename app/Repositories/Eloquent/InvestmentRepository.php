<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Models\Investment;
use App\Repositories\Contracts\InvestmentRepositoryInterface;
use Illuminate\Support\Collection;

class InvestmentRepository implements InvestmentRepositoryInterface
{
    public function allForAccount(Account $account): Collection
    {
        return Investment::where('account_id', $account->id)->orderBy('type')->get();
    }

    public function firstOrCreate(Account $account, string $type): Investment
    {
        return Investment::firstOrCreate(
            ['account_id' => $account->id, 'type' => $type],
            ['balance' => 0]
        );
    }

    public function addToBalance(Investment $investment, float $amount): Investment
    {
        $investment->balance = (float) $investment->balance + $amount;
        $investment->save();

        return $investment;
    }
}
