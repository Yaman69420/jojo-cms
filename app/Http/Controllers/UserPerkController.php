<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Favorite;
use App\Models\Part;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPerkController extends Controller
{
    public function rate(Request $request, Episode $episode)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'episode_id' => $episode->id],
            ['rating' => $validated['rating']]
        );

        // Automatically mark as watched if not already
        $user = Auth::user();
        if (! $user->watchedEpisodes()->where('episode_id', $episode->id)->exists()) {
            $user->watchedEpisodes()->attach($episode->id);
        }

        return back()->with('success', 'Your rating has been recorded and the episode marked as watched.');
    }

    public function deleteRating(Episode $episode)
    {
        Rating::where('user_id', Auth::id())
            ->where('episode_id', $episode->id)
            ->delete();

        return back()->with('success', 'Your rating has been removed.');
    }

    public function toggleFavorite(Request $request, $type, $id)
    {
        $model = match ($type) {
            'part' => Part::findOrFail($id),
            'episode' => Episode::findOrFail($id),
            default => abort(404),
        };

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('favoritable_id', $model->id)
            ->where('favoritable_type', get_class($model))
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Removed from favorites.';
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'favoritable_id' => $model->id,
                'favoritable_type' => get_class($model),
            ]);
            $message = 'Added to favorites.';
        }

        return back()->with('success', $message);
    }

    public function toggleWatched(Request $request, Episode $episode)
    {
        $user = Auth::user();

        if ($user->watchedEpisodes()->where('episode_id', $episode->id)->exists()) {
            $user->watchedEpisodes()->detach($episode->id);

            // Also remove rating if it exists
            Rating::where('user_id', $user->id)
                ->where('episode_id', $episode->id)
                ->delete();

            $message = 'Episode marked as not watched and rating removed.';
        } else {
            $user->watchedEpisodes()->attach($episode->id);
            $message = 'Episode marked as watched.';
        }

        return back()->with('success', $message);
    }
}
