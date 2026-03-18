<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'avatar_url',
    ];

    /**
     * Get the posts for the user.
     *
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the user's media.
     *
     * @return MorphMany<Media, $this>
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the ratings for the user.
     *
     * @return HasMany<Rating, $this>
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get all of the user's favorites.
     *
     * @return HasMany<Favorite, $this>
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * The episodes that the user has watched.
     */
    public function watchedEpisodes()
    {
        return $this->belongsToMany(Episode::class, 'watched_episodes')->withTimestamps();
    }

    /**
     * Friendships that this user started.
     */
    public function friendshipsSent()
    {
        return $this->belongsToMany(User::class, 'friendships', 'sender_id', 'recipient_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Friendships that were sent to this user.
     */
    public function friendshipsReceived()
    {
        return $this->belongsToMany(User::class, 'friendships', 'recipient_id', 'sender_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Get all accepted friends.
     */
    public function friends()
    {
        $sent = $this->friendshipsSent()->wherePivot('status', 'accepted')->get();
        $received = $this->friendshipsReceived()->wherePivot('status', 'accepted')->get();
        
        return $sent->merge($received);
    }

    /**
     * Check if the user is friends with another user.
     */
    public function isFriendsWith(User $user): bool
    {
        return $this->friendshipsSent()->wherePivot('status', 'accepted')->where('recipient_id', $user->id)->exists() ||
               $this->friendshipsReceived()->wherePivot('status', 'accepted')->where('sender_id', $user->id)->exists();
    }

    /**
     * Check if there's a pending request from this user to another.
     */
    public function hasSentRequestTo(User $user): bool
    {
        return $this->friendshipsSent()->wherePivot('status', 'pending')->where('recipient_id', $user->id)->exists();
    }

    /**
     * Check if there's a pending request to this user from another.
     */
    public function hasPendingRequestFrom(User $user): bool
    {
        return $this->friendshipsReceived()->wherePivot('status', 'pending')->where('sender_id', $user->id)->exists();
    }

    /**
     * Get all conversations this user is part of.
     */
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id)
            ->orderBy('last_message_at', 'desc');
    }

    /**
     * Get or create a conversation between two users.
     */
    public function getConversationWith(User $otherUser): Conversation
    {
        $conversation = Conversation::where(function ($query) use ($otherUser) {
            $query->where('user_one_id', $this->id)->where('user_two_id', $otherUser->id);
        })->orWhere(function ($query) use ($otherUser) {
            $query->where('user_one_id', $otherUser->id)->where('user_two_id', $this->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $this->id,
                'user_two_id' => $otherUser->id,
            ]);
        }

        return $conversation;
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
