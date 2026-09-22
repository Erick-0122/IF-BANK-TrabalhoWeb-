<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLimitRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'requested_limit' => ['required', 'numeric', 'min:0.01'],
            'justification' => ['nullable', 'string', 'max:500'],
        ];
    }
}
