<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class User extends Authenticatable implements AuditableContract
{
    use HasApiTokens, HasFactory, Notifiable, Auditable;

    public const ROLE_GERENTE_GERAL = 'gerente_geral';
    public const ROLE_GERENTE_CONTA = 'gerente_conta';
    public const ROLE_CLIENTE = 'cliente';

    protected $fillable = ['name', 'email', 'password', 'role', 'active'];

    protected $hidden = ['password', 'remember_token'];

    /** Campos que o Laravel Auditing deve registrar */
    protected $auditInclude = ['name', 'email', 'role', 'active'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    /** Conta do cliente */
    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }

    /** Contas sob responsabilidade de um gerente de conta */
    public function managedAccounts(): HasMany
    {
        return $this->hasMany(Account::class, 'manager_id');
    }

    public function isGerenteGeral(): bool
    {
        return $this->role === self::ROLE_GERENTE_GERAL;
    }

    public function isGerenteConta(): bool
    {
        return $this->role === self::ROLE_GERENTE_CONTA;
    }

    public function isCliente(): bool
    {
        return $this->role === self::ROLE_CLIENTE;
    }
}
