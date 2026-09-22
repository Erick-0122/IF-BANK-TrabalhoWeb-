<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Account::all() as $account) {
            $balance = (float) $account->balance;

            $movements = [
                [Transaction::PIX_RECEBIDO, 1200.00, null, 'Salário'],
                [Transaction::PIX_ENVIADO, -230.40, null, 'Aluguel'],
                [Transaction::APLICACAO, -750.00, 'POUPANCA', 'Aplicação em POUPANCA'],
                [Transaction::PIX_ENVIADO, -89.90, null, 'Mercado'],
            ];

            foreach ($movements as $i => [$type, $amount, $investment, $description]) {
                Transaction::create([
                    'account_id' => $account->id,
                    'type' => $type,
                    'investment_type' => $investment,
                    'amount' => $amount,
                    'balance_after' => $balance,
                    'description' => $description,
                    'created_at' => now()->subDays(20 - ($i * 5)),
                    'updated_at' => now()->subDays(20 - ($i * 5)),
                ]);
            }
        }
    }
}
