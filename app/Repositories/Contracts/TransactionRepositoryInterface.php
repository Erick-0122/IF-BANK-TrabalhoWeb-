<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;

    /** Extrato por periodo (datas no formato Y-m-d) */
    public function statement(Account $account, ?string $from = null, ?string $to = null): Collection;
}
