<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use App\Models\Investment;
use Illuminate\Support\Collection;

interface InvestmentRepositoryInterface
{
    public function allForAccount(Account $account): Collection;

    public function firstOrCreate(Account $account, string $type): Investment;

    public function addToBalance(Investment $investment, float $amount): Investment;
}
