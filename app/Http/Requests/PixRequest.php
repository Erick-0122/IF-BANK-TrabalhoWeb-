<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PixRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pix_key' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
