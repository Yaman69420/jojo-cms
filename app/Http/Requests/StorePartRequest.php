<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'number' => ['required', 'integer', 'min:1'],
            'release_year' => ['required', 'integer', 'min:1900', 'max:2100'],
            'summary' => ['nullable', 'string'],
            'trailer_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'max:20480'],
        ];
    }
}
