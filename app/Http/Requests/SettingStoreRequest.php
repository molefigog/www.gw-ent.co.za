<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingStoreRequest extends FormRequest
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
            'site' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:655', 'string'],
            'tagline' => ['required', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'logo' => ['image', 'max:1024', 'required'],
            'favicon' => ['image', 'max:1024', 'required'],
        ];
    }
}
