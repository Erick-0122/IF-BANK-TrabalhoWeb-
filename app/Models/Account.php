<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Account extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['user_id', 'manager_id', 'balance', 'limit', 'blocked', 'block_reason'];

    protected $auditInclude = ['balance', 'limit', 'blocked', 'block_reason', 'manager_id'];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'limit' => 'decimal:2',
            'blocked' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function limitRequests(): HasMany
    {
        return $this->hasMany(LimitRequest::class);
    }

    /** Numero da conta = chave primaria formatada */
    public function getNumberAttribute(): string
    {
        return str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    /** Saldo + limite disponivel para gastar */
    public function availableBalance(): float
    {
        return (float) $this->balance + (float) $this->limit;
    }
}
