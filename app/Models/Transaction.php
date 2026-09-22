<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public const PIX_ENVIADO = 'pix_enviado';
    public const PIX_RECEBIDO = 'pix_recebido';
    public const APLICACAO = 'aplicacao';
    public const RESGATE = 'resgate';

    protected $fillable = [
        'account_id', 'type', 'investment_type', 'amount',
        'balance_after', 'description', 'counterpart',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /** Movimentacao positiva = entrou na conta */
    public function isCredit(): bool
    {
        return (float) $this->amount >= 0;
    }

    public function getLabelAttribute(): string
    {
        return match ($this->type) {
            self::PIX_ENVIADO => 'Pix enviado',
            self::PIX_RECEBIDO => 'Pix recebido',
            self::APLICACAO => "Aplicação em {$this->investment_type}",
            self::RESGATE => "Resgate de {$this->investment_type}",
        };
    }
}
