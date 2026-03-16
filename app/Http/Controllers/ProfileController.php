<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Load favorites with their respective models
        $favorites = $user->favorites()->with('favoritable')->latest()->get();

        // Load watched episodes with parts
        $watchedEpisodes = $user->watchedEpisodes()->with('part')->latest()->get();

        return view('profile.show', compact('user', 'favorites', 'watchedEpisodes'));
    }
}
