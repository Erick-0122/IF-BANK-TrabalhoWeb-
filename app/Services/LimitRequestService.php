<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Account;
use App\Models\LimitRequest;
use App\Models\User;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\LimitRequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Solicitacao (gerente de conta) e aprovacao/reprovacao (gerente geral) de limite.
 */
class LimitRequestService
{
    public function __construct(
        private readonly LimitRequestRepositoryInterface $requests,
        private readonly AccountRepositoryInterface $accounts,
    ) {}

    public function list(?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->requests->paginate($status, $perPage);
    }

    public function request(Account $account, User $manager, float $newLimit, ?string $justification): LimitRequest
    {
        if ($newLimit <= (float) $account->limit) {
            throw new BusinessRuleException('O novo limite deve ser maior que o limite atual.');
        }

        if ($this->requests->hasPendingForAccount($account->id)) {
            throw new BusinessRuleException('Já existe uma solicitação pendente para esta conta.');
        }

        return $this->requests->create([
            'account_id' => $account->id,
            'requested_by' => $manager->id,
            'current_limit' => $account->limit,
            'requested_limit' => $newLimit,
            'justification' => $justification,
            'status' => LimitRequest::PENDENTE,
        ]);
    }

    public function approve(LimitRequest $request, User $generalManager): LimitRequest
    {
        $this->assertPending($request);

        return DB::transaction(function () use ($request, $generalManager) {
            $this->accounts->update($request->account, ['limit' => $request->requested_limit]);

            return $this->requests->update($request, [
                'status' => LimitRequest::APROVADA,
                'reviewed_by' => $generalManager->id,
                'reviewed_at' => now(),
            ]);
        });
    }

    public function reject(LimitRequest $request, User $generalManager): LimitRequest
    {
        $this->assertPending($request);

        return $this->requests->update($request, [
            'status' => LimitRequest::REPROVADA,
            'reviewed_by' => $generalManager->id,
            'reviewed_at' => now(),
        ]);
    }

    private function assertPending(LimitRequest $request): void
    {
        if (! $request->isPending()) {
            throw new BusinessRuleException('Esta solicitação já foi analisada.');
        }
    }
}
