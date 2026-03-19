<?php

namespace App\Models;

use Database\Factories\EpisodeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Episode extends Model
{
    /** @use HasFactory<EpisodeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'part_id',
        'title',
        'episode_number',
        'release_date',
        'imdb_score',
        'summary',
        'thumbnail_url',
    ];

    /**
     * Get the episode's thumbnail URL.
     */
    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                $url = $this->thumbnail_url;
                if (! $url) {
                    return null;
                }
                if (str_starts_with($url, 'http')) {
                    return $url;
                }

                return asset('storage/'.$url);
            },
        );
    }

    /**
     * Get the part that owns the episode.
     *
     * @return BelongsTo<Part, $this>
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    /**
     * Get the episode's media.
     *
     * @return MorphMany<Media, $this>
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the ratings for the episode.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the average rating for the episode.
     */
    public function averageRating(): float
    {
        return (float) $this->ratings()->avg('rating');
    }

    /**
     * Get the favorites for the episode.
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * The users that have watched this episode.
     */
    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'watched_episodes')->withTimestamps();
    }

    /**
     * Check if the authenticated user has watched this episode.
     */
    public function isWatchedByAuthUser(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->watchers()->where('user_id', Auth::id())->exists();
    }

    /**
     * Check if the authenticated user has favorited this episode.
     */
    public function isFavoritedByAuthUser(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->favorites()->where('user_id', Auth::id())->exists();
    }

    /**
     * Get the authenticated user's rating for this episode.
     */
    public function authUserRating(): ?int
    {
        if (! Auth::check()) {
            return null;
        }

        return $this->ratings()->where('user_id', Auth::id())->value('rating');
    }

    /**
     * Scope a query to filter episodes.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('summary', 'like', "%{$search}%");
        })->when($filters['part_id'] ?? null, function ($query, $partId) {
            $query->where('part_id', $partId);
        });
    }
}
