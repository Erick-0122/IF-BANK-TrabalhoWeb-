<?php

namespace App\Providers;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\AuditRepositoryInterface;
use App\Repositories\Contracts\InvestmentRepositoryInterface;
use App\Repositories\Contracts\LimitRequestRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\AccountRepository;
use App\Repositories\Eloquent\AuditRepository;
use App\Repositories\Eloquent\InvestmentRepository;
use App\Repositories\Eloquent\LimitRequestRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Amarra as interfaces (Contracts) as implementacoes Eloquent - padrao CSR.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        AccountRepositoryInterface::class => AccountRepository::class,
        TransactionRepositoryInterface::class => TransactionRepository::class,
        InvestmentRepositoryInterface::class => InvestmentRepository::class,
        LimitRequestRepositoryInterface::class => LimitRequestRepository::class,
        AuditRepositoryInterface::class => AuditRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->bindings as $contract => $implementation) {
            $this->app->bind($contract, $implementation);
        }
    }
}
