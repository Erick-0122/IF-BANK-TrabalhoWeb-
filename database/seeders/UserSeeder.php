<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Gil Eduardo de Andrade',
            'email' => 'gerente.geral@ifbank.test',
            'password' => 'password',
            'role' => User::ROLE_GERENTE_GERAL,
            'email_verified_at' => now(),
        ]);

        foreach ([['Marina Duarte', 'marina@ifbank.test'], ['Rafael Lima', 'rafael@ifbank.test']] as [$name, $email]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => 'password',
                'role' => User::ROLE_GERENTE_CONTA,
                'email_verified_at' => now(),
            ]);
        }
    }
}
