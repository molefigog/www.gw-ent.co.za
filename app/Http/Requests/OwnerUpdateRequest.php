<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => ['email'],
            'whatsapp' => ['max:255', 'string'],
            'facebook' => ['max:255', 'string'],
            'address' => ['max:255', 'string'],
        ];
    }
}
