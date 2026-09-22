<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateClientAccountRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('conta')?->user_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'balance' => ['required', 'numeric', 'min:0'],
            'limit' => ['required', 'numeric', 'min:0'],
        ];
    }
}
