<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GoogleCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'error' => ['nullable', 'string'],
            'error_description' => ['nullable', 'string'],
        ];
    }
}
