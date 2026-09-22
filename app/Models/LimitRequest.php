<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class LimitRequest extends Model implements AuditableContract
{
    use Auditable;

    public const PENDENTE = 'pendente';
    public const APROVADA = 'aprovada';
    public const REPROVADA = 'reprovada';

    protected $fillable = [
        'account_id', 'requested_by', 'reviewed_by', 'current_limit',
        'requested_limit', 'justification', 'status', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'current_limit' => 'decimal:2',
            'requested_limit' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDENTE;
    }
}
