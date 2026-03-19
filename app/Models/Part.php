<?php

namespace App\Models;

use Database\Factories\PartFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Part extends Model
{
    /** @use HasFactory<PartFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Cast key to string for Postgres VARCHAR morph compatibility.
     */
    public function getKeyForMorphQuery(): string
    {
        return (string) $this->getKey();
    }

    protected $fillable = [
        'title',
        'number',
        'release_year',
        'summary',
        'trailer_url',
        'poster_path',
    ];

    /**
     * Get the part's poster URL.
     */
    protected function poster(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                $url = $this->poster_path;
                if (! $url) {
                    // Fallback to Media model if exists
                    $media = $this->media()->first();
                    if ($media) {
                        return asset('storage/'.$media->path);
                    }

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
     * Get the episodes for the season.
     *
     * @return HasMany<Episode, $this>
     */
    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    /**
     * Get the season's media.
     *
     * @return MorphMany<Media, $this>
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the favorites for the season.
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Check if the authenticated user has favorited this season.
     */
    public function isFavoritedByAuthUser(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->favorites()->where('user_id', Auth::id())->exists();
    }

    /**
     * Scope a query to filter seasons.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('summary', 'like', "%{$search}%");
        });
    }
}
