<?php

namespace App\Policies;

use App\Models\User;

class AuditPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerenteGeral();
    }
}
