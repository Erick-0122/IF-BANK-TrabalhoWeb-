<?php

namespace App\Services;

use App\Mail\AccountCredentialsMail;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Regras de negocio do CRUD de Gerentes de Conta (acesso: gerente geral).
 */
class ManagerService
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    public function list(int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginateByRole(User::ROLE_GERENTE_CONTA, $perPage);
    }

    public function create(array $data): User
    {
        $plainPassword = $data['password'];

        $manager = DB::transaction(fn () => $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $plainPassword,
            'role' => User::ROLE_GERENTE_CONTA,
            'active' => true,
        ]));

        Mail::to($manager->email)->send(
            new AccountCredentialsMail($manager, $plainPassword, 'Gerente de Conta')
        );

        return $manager;
    }

    public function update(User $manager, array $data): User
    {
        $payload = ['name' => $data['name'], 'email' => $data['email']];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        if (array_key_exists('active', $data)) {
            $payload['active'] = (bool) $data['active'];
        }

        return $this->users->update($manager, $payload);
    }

    public function delete(User $manager): void
    {
        // As contas de clientes ficam sem gerente responsavel (nullOnDelete)
        $this->users->delete($manager);
    }
}
