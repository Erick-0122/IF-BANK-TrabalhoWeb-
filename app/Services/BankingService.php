<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Account;
use App\Models\Investment;
use App\Models\Transaction;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\InvestmentRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Movimentacoes financeiras do cliente: pix, aplicacoes e resgates.
 * Toda movimentacao gera um registro em transactions com valor sinalizado.
 */
class BankingService
{
    public function __construct(
        private readonly AccountRepositoryInterface $accounts,
        private readonly TransactionRepositoryInterface $transactions,
        private readonly InvestmentRepositoryInterface $investments,
    ) {}

    public function investments(Account $account): Collection
    {
        foreach (Investment::TYPES as $type) {
            $this->investments->firstOrCreate($account, $type);
        }

        return $this->investments->allForAccount($account);
    }

    public function sendPix(Account $account, string $pixKey, float $amount, ?string $description = null): Transaction
    {
        $this->assertActive($account);
        $this->assertPositive($amount);

        return DB::transaction(function () use ($account, $pixKey, $amount, $description) {
            $account = Account::lockForUpdate()->find($account->id);

            if ($account->availableBalance() < $amount) {
                throw new BusinessRuleException('Saldo e limite insuficientes para esta transferência.');
            }

            $this->accounts->addToBalance($account, -$amount);

            $destination = Account::whereHas('user', fn ($q) => $q->where('email', $pixKey))->first();

            if ($destination && $destination->id !== $account->id) {
                $this->accounts->addToBalance($destination, $amount);
                $this->transactions->create([
                    'account_id' => $destination->id,
                    'type' => Transaction::PIX_RECEBIDO,
                    'amount' => $amount,
                    'balance_after' => $destination->fresh()->balance,
                    'description' => $description,
                    'counterpart' => $account->user->email,
                ]);
            }

            return $this->transactions->create([
                'account_id' => $account->id,
                'type' => Transaction::PIX_ENVIADO,
                'amount' => -$amount,
                'balance_after' => $account->fresh()->balance,
                'description' => $description,
                'counterpart' => $pixKey,
            ]);
        });
    }

    public function invest(Account $account, string $type, float $amount): Transaction
    {
        $this->assertActive($account);
        $this->assertPositive($amount);
        $this->assertType($type);

        return DB::transaction(function () use ($account, $type, $amount) {
            $account = Account::lockForUpdate()->find($account->id);

            if ((float) $account->balance < $amount) {
                throw new BusinessRuleException('Saldo insuficiente para aplicar. O limite não pode ser investido.');
            }

            $this->accounts->addToBalance($account, -$amount);
            $investment = $this->investments->firstOrCreate($account, $type);
            $this->investments->addToBalance($investment, $amount);

            return $this->transactions->create([
                'account_id' => $account->id,
                'type' => Transaction::APLICACAO,
                'investment_type' => $type,
                'amount' => -$amount,
                'balance_after' => $account->fresh()->balance,
                'description' => "Aplicação em {$type}",
            ]);
        });
    }

    public function redeem(Account $account, string $type, float $amount): Transaction
    {
        $this->assertActive($account);
        $this->assertPositive($amount);
        $this->assertType($type);

        return DB::transaction(function () use ($account, $type, $amount) {
            $account = Account::lockForUpdate()->find($account->id);
            $investment = $this->investments->firstOrCreate($account, $type);

            if ((float) $investment->balance < $amount) {
                throw new BusinessRuleException("Valor aplicado em {$type} é insuficiente para este resgate.");
            }

            $this->investments->addToBalance($investment, -$amount);
            $this->accounts->addToBalance($account, $amount);

            return $this->transactions->create([
                'account_id' => $account->id,
                'type' => Transaction::RESGATE,
                'investment_type' => $type,
                'amount' => $amount,
                'balance_after' => $account->fresh()->balance,
                'description' => "Resgate de {$type}",
            ]);
        });
    }

    private function assertActive(Account $account): void
    {
        if ($account->blocked) {
            throw new BusinessRuleException('Conta bloqueada por suspeita de fraude. Apenas a consulta de saldo está disponível.', 403);
        }
    }

    private function assertPositive(float $amount): void
    {
        if ($amount <= 0) {
            throw new BusinessRuleException('O valor deve ser maior que zero.');
        }
    }

    private function assertType(string $type): void
    {
        if (! in_array($type, Investment::TYPES, true)) {
            throw new BusinessRuleException('Tipo de investimento inválido.');
        }
    }
}
