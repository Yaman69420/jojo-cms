<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'part_id' => ['required', 'exists:parts,id'],
            'title' => ['required', 'string', 'max:255'],
            'episode_number' => ['required', 'integer', 'min:1'],
            'release_date' => ['required', 'date'],
            'imdb_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'summary' => ['nullable', 'string'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'max:2048'],
        ];
    }
}
