<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicStoreRequest extends FormRequest
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
            'artist' => ['required', 'max:255', 'string'],
            'title' => ['required', 'max:255', 'string'],
            'amount' => ['required', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            // 'demo' => ['file', 'max:12024', 'required'],
            'file' => ['required', 'file', 'max:20000'],
            'description' => ['required', 'max:255', 'string'],

        ];
    }
}
