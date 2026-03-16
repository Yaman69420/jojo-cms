<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Episode;
use App\Models\Part;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        $episodes = Episode::query()
            ->with(['part'])
            ->withAvg('ratings as ratings_avg_rating', 'rating')
            ->filter($request->only(['search', 'part_id']))
            ->orderBy('release_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $parts = Part::all();

        return view('admin.episodes.index', compact('episodes', 'parts'));
    }

    public function create()
    {
        $parts = Part::all();

        return view('admin.episodes.create', compact('parts'));
    }

    public function store(StoreEpisodeRequest $request)
    {
        $episode = Episode::create($request->validated());

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');
                $episode->media()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.episodes.index')->with('success', 'Episode saved successfully.');
    }

    public function show(Episode $episode)
    {
        $episode->load(['part', 'media']);

        return view('admin.episodes.show', compact('episode'));
    }

    public function edit(Episode $episode)
    {
        $parts = Part::all();

        return view('admin.episodes.edit', compact('episode', 'parts'));
    }

    public function update(UpdateEpisodeRequest $request, Episode $episode)
    {
        $episode->update($request->validated());

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');
                $episode->media()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.episodes.index')->with('success', 'Episode details have evolved!');
    }

    public function destroy(Episode $episode)
    {
        $episode->delete();

        return redirect()->route('admin.episodes.index')->with('success', 'Episode erased! KING CRIMSON!');
    }
}
