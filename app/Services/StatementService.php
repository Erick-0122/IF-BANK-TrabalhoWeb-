<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Account;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Extrato bancario, usado tanto pelo gerente de conta quanto pelo cliente.
 */
class StatementService
{
    public function __construct(private readonly TransactionRepositoryInterface $transactions) {}

    public function generate(Account $account, ?string $from = null, ?string $to = null, bool $enforceBlock = false): array
    {
        if ($enforceBlock && $account->blocked) {
            throw new BusinessRuleException('Conta bloqueada. Não é possível gerar extrato.', 403);
        }

        if ($from && $to && $from > $to) {
            throw new BusinessRuleException('A data inicial não pode ser maior que a data final.');
        }

        $items = $this->transactions->statement($account, $from, $to);

        return [
            'account' => $account,
            'from' => $from,
            'to' => $to,
            'transactions' => $items,
            'credits' => $this->sum($items, true),
            'debits' => $this->sum($items, false),
            'balance' => (float) $account->balance,
        ];
    }

    private function sum(Collection $items, bool $credit): float
    {
        return (float) $items
            ->filter(fn ($t) => $credit ? (float) $t->amount >= 0 : (float) $t->amount < 0)
            ->sum(fn ($t) => abs((float) $t->amount));
    }
}
