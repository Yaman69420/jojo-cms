<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Part;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'release_date');
        $direction = $request->input('direction', 'asc');

        // Validate sort field
        $allowedSorts = ['release_date', 'imdb_score', 'ratings_avg_rating', 'title'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'release_date';
        }

        // Validate direction
        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $episodes = Episode::query()
            ->with(['part', 'media'])
            ->withAvg('ratings as ratings_avg_rating', 'rating')
            ->filter($request->only(['search', 'part_id']))
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        $parts = Part::all();

        return view('episodes.index', compact('episodes', 'parts', 'sort', 'direction'));
    }

    public function show(Episode $episode)
    {
        $episode->load(['part', 'media']);

        return view('episodes.show', compact('episode'));
    }
}
