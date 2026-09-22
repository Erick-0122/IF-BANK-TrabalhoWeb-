<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class AccountPolicy
{
    /** Gerentes de conta mantem as contas dos clientes. */
    public function viewAny(User $user): bool
    {
        return $user->isGerenteConta();
    }

    public function view(User $user, Account $account): bool
    {
        return $user->isGerenteConta() || $account->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isGerenteConta();
    }

    public function update(User $user, Account $account): bool
    {
        return $user->isGerenteConta();
    }

    public function delete(User $user, Account $account): bool
    {
        return $user->isGerenteConta();
    }

    public function block(User $user, Account $account): bool
    {
        return $user->isGerenteConta();
    }

    /** Extrato: gerente de conta ou o proprio dono da conta. */
    public function viewStatement(User $user, Account $account): bool
    {
        return $user->isGerenteConta() || $account->user_id === $user->id;
    }

    /** Movimentar: apenas o dono, com a conta desbloqueada. */
    public function transact(User $user, Account $account): bool
    {
        return $account->user_id === $user->id && ! $account->blocked;
    }
}
