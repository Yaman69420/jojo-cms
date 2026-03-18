<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\FriendRequestReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    /**
     * Send a friend request.
     */
    public function send(User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot send a friend request to yourself.');
        }

        if ($currentUser->isFriendsWith($user) || $currentUser->hasSentRequestTo($user) || $user->hasSentRequestTo($currentUser)) {
            return back()->with('error', 'A friendship or pending request already exists.');
        }

        $currentUser->friendshipsSent()->attach($user->id, ['status' => 'pending']);
        
        $user->notify(new FriendRequestReceived($currentUser));

        return back()->with('status', 'Friend request sent!');
    }

    /**
     * Accept a friend request.
     */
    public function accept(User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        if (!$currentUser->hasPendingRequestFrom($user)) {
            return back()->with('error', 'No pending friend request found.');
        }

        $currentUser->friendshipsReceived()->updateExistingPivot($user->id, ['status' => 'accepted']);
        
        $user->notify(new FriendRequestAccepted($currentUser));

        return back()->with('status', 'Friend request accepted!');
    }

    /**
     * Reject a friend request.
     */
    public function reject(User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        if (!$currentUser->hasPendingRequestFrom($user)) {
            return back()->with('error', 'No pending friend request found.');
        }

        $currentUser->friendshipsReceived()->detach($user->id);

        return back()->with('status', 'Friend request rejected.');
    }

    /**
     * Unfriend a user or cancel a request.
     */
    public function unfriend(User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        $currentUser->friendshipsSent()->detach($user->id);
        $currentUser->friendshipsReceived()->detach($user->id);

        return back()->with('status', 'Friendship removed.');
    }

    /**
     * Show all pending friend requests.
     */
    public function requests()
    {
        $requests = Auth::user()->friendshipsReceived()->wherePivot('status', 'pending')->get();
        
        return view('profile.requests', compact('requests'));
    }
}
