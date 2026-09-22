<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AuditRepositoryInterface
{
    /** Logs das atividades dos gerentes de conta */
    public function paginateManagerActivity(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
