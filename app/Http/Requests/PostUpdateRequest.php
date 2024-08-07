<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'description' => ['nullable', 'max:255', 'string'],
            'content' => ['nullable', 'max:255', 'string'],
            'category' => ['nullable', 'max:255', 'string'],
        ];
    }
}
