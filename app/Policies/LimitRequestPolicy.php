<?php

namespace App\Policies;

use App\Models\LimitRequest;
use App\Models\User;

class LimitRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGerenteGeral() || $user->isGerenteConta();
    }

    /** Quem solicita e o gerente de conta. */
    public function create(User $user): bool
    {
        return $user->isGerenteConta();
    }

    /** Quem aprova/reprova e o gerente geral. */
    public function review(User $user, LimitRequest $request): bool
    {
        return $user->isGerenteGeral() && $request->isPending();
    }
}
