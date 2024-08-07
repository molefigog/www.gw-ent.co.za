<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicUpdateRequest extends FormRequest
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
            'genre_id' => ['required', 'integer'],
            'artist' => ['max:255', 'string'],
            'title' => ['max:255', 'string'],
            'amount' => ['max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'demo' => ['file', 'max:6024'],
            'file' => ['file', 'max:20000'],
            'description' => ['max:255', 'string'],

        ];
    }
}
