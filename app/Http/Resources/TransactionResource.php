<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'investment_type' => $this->investment_type,
            'amount' => (float) $this->amount,
            'direction' => $this->isCredit() ? 'credito' : 'debito',
            'balance_after' => (float) $this->balance_after,
            'description' => $this->description,
            'counterpart' => $this->counterpart,
            'date' => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
