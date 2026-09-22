<?php

namespace App\Policies;

use App\Models\User;

/** Somente o gerente geral mantem os gerentes de conta. */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerenteGeral();
    }

    public function create(User $user): bool
    {
        return $user->isGerenteGeral();
    }

    public function update(User $user, User $manager): bool
    {
        return $user->isGerenteGeral() && $manager->isGerenteConta();
    }

    public function delete(User $user, User $manager): bool
    {
        return $user->isGerenteGeral() && $manager->isGerenteConta() && $user->id !== $manager->id;
    }
}
