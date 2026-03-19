<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Part;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'parts' => Part::count(),
            'episodes' => Episode::count(),
            'users' => User::count(),
        ];

        $latestEpisode = Episode::with(['part'])
            ->join('parts', 'episodes.part_id', '=', 'parts.id')
            ->orderBy('parts.number', 'desc')
            ->orderBy('episodes.episode_number', 'desc')
            ->select('episodes.*')
            ->first();

        $topEpisodes = Episode::query()
            ->with(['part'])
            ->withAvg('ratings as ratings_avg_rating', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('imdb_score')
            ->take(3)
            ->get();

        $userActivity = null;
        if (Auth::check()) {
            $user = Auth::user();
            $userActivity = [
                'lastWatched' => $user->watchedEpisodes()->with('part')->latest('watched_episodes.created_at')->first(),
                'favoritesCount' => $user->favorites()->count(),
                'ratingsCount' => Rating::where('user_id', $user->id)->count(),
            ];
        }

        return view('home', compact('stats', 'latestEpisode', 'topEpisodes', 'userActivity'));
    }
}
