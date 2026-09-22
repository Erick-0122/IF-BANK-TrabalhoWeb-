<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Mail\AccountCredentialsMail;
use App\Models\Account;
use App\Models\Investment;
use App\Models\User;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\InvestmentRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Regras de negocio do CRUD de contas de clientes (acesso: gerente de conta).
 */
class ClientAccountService
{
    public function __construct(
        private readonly AccountRepositoryInterface $accounts,
        private readonly UserRepositoryInterface $users,
        private readonly InvestmentRepositoryInterface $investments,
    ) {}

    public function list(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->accounts->paginate($perPage, $search);
    }

    public function find(int $id): Account
    {
        $account = $this->accounts->find($id);

        if (! $account) {
            throw new BusinessRuleException('Conta não encontrada.', 404);
        }

        return $account;
    }

    public function create(array $data, User $manager): Account
    {
        $plainPassword = $data['password'];

        $account = DB::transaction(function () use ($data, $manager, $plainPassword) {
            $client = $this->users->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $plainPassword,
                'role' => User::ROLE_CLIENTE,
                'active' => true,
            ]);

            $account = $this->accounts->create([
                'user_id' => $client->id,
                'manager_id' => $manager->id,
                'balance' => $data['balance'] ?? 0,
                'limit' => $data['limit'] ?? 0,
                'blocked' => false,
            ]);

            foreach (Investment::TYPES as $type) {
                $this->investments->firstOrCreate($account, $type);
            }

            return $account;
        });

        Mail::to($account->user->email)->send(
            new AccountCredentialsMail($account->user, $plainPassword, 'Cliente', $account->number)
        );

        return $account;
    }

    public function update(Account $account, array $data): Account
    {
        DB::transaction(function () use ($account, $data) {
            $payload = ['name' => $data['name'], 'email' => $data['email']];

            if (! empty($data['password'])) {
                $payload['password'] = $data['password'];
            }

            $this->users->update($account->user, $payload);

            $this->accounts->update($account, [
                'balance' => $data['balance'] ?? $account->balance,
                'limit' => $data['limit'] ?? $account->limit,
            ]);
        });

        return $account->fresh(['user']);
    }

    public function delete(Account $account): void
    {
        DB::transaction(function () use ($account) {
            $user = $account->user;
            $this->accounts->delete($account);
            $this->users->delete($user);
        });
    }

    public function block(Account $account, string $reason): Account
    {
        if ($account->blocked) {
            throw new BusinessRuleException('Esta conta já está bloqueada.');
        }

        return $this->accounts->update($account, [
            'blocked' => true,
            'block_reason' => $reason,
        ]);
    }

    public function unblock(Account $account): Account
    {
        if (! $account->blocked) {
            throw new BusinessRuleException('Esta conta não está bloqueada.');
        }

        return $this->accounts->update($account, [
            'blocked' => false,
            'block_reason' => null,
        ]);
    }
}
