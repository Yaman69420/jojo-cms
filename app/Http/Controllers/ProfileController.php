<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of users (Search).
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
        }

        $users = $query->where('id', '!=', Auth::id())
                       ->paginate(12)
                       ->withQueryString();

        return view('profile.index', [
            'users' => $users,
            'searchTerm' => $request->get('search'),
        ]);
    }

    /**
     * Show the user's profile.
     */
    public function show(User $user)
    {
        $user->loadCount(['watchedEpisodes', 'favorites', 'ratings']);
        
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar_url' => ['nullable', 'string'],
        ]);

        $user->update($validated);

        return back()->with('status', 'profile-updated');
    }
}
