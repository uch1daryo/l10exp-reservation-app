<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_name' => ['required'],
            'user_email' => ['required', 'email'],
            'purpose' => ['required'],
            'start_at' => ['required', 'before:end_at'],
            'end_at' => ['required', 'after:start_at'],
            'note' => ['nullable'],
        ];
    }
}
