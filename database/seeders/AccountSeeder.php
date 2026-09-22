<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $managers = User::where('role', User::ROLE_GERENTE_CONTA)->get();

        $clients = [
            ['Ana Paula Ribeiro', 'ana@cliente.test', 4800.00, 1500.00],
            ['Bruno Tavares', 'bruno@cliente.test', 1250.50, 500.00],
            ['Carla Menezes', 'carla@cliente.test', 9800.00, 3000.00],
            ['Diego Fontes', 'diego@cliente.test', 320.75, 200.00],
        ];

        foreach ($clients as $i => [$name, $email, $balance, $limit]) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => 'password',
                'role' => User::ROLE_CLIENTE,
                'email_verified_at' => now(),
            ]);

            $account = Account::create([
                'user_id' => $user->id,
                'manager_id' => $managers[$i % $managers->count()]->id,
                'balance' => $balance,
                'limit' => $limit,
            ]);

            foreach (Investment::TYPES as $type) {
                Investment::create([
                    'account_id' => $account->id,
                    'type' => $type,
                    'balance' => $type === 'POUPANCA' ? 750.00 : 0,
                ]);
            }
        }
    }
}
