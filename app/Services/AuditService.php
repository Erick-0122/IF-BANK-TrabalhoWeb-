<?php

namespace App\Services;

use App\Repositories\Contracts\AuditRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Consulta dos logs de auditoria (acesso: gerente geral).
 */
class AuditService
{
    public function __construct(private readonly AuditRepositoryInterface $audits) {}

    public function managerActivity(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->audits->paginateManagerActivity($filters, $perPage);
    }
}
