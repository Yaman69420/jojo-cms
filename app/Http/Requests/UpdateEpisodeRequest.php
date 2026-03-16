<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'part_id' => ['sometimes', 'exists:parts,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'episode_number' => ['sometimes', 'integer', 'min:1'],
            'release_date' => ['sometimes', 'date'],
            'imdb_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'summary' => ['nullable', 'string'],
            'media' => ['nullable', 'array'],
            'media.*' => ['image', 'max:2048'],
        ];
    }
}
