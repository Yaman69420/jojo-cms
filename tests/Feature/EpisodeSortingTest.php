<?php

namespace Tests\Feature;

use App\Models\Episode;
use App\Models\Part;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpisodeSortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_sort_episodes_by_release_date(): void
    {
        $part = Part::factory()->create();
        $episode1 = Episode::factory()->create([
            'part_id' => $part->id,
            'release_date' => '2020-01-01',
        ]);
        $episode2 = Episode::factory()->create([
            'part_id' => $part->id,
            'release_date' => '2021-01-01',
        ]);

        // Ascending order (new default)
        $response = $this->get(route('episodes.index', ['sort' => 'release_date', 'direction' => 'asc']));
        $response->assertSeeInOrder([$episode1->title, $episode2->title]);

        // Descending order
        $response = $this->get(route('episodes.index', ['sort' => 'release_date', 'direction' => 'desc']));
        $response->assertSeeInOrder([$episode2->title, $episode1->title]);
    }

    public function test_can_sort_episodes_by_imdb_score(): void
    {
        $part = Part::factory()->create();
        $episode1 = Episode::factory()->create([
            'part_id' => $part->id,
            'imdb_score' => 8.5,
        ]);
        $episode2 = Episode::factory()->create([
            'part_id' => $part->id,
            'imdb_score' => 9.5,
        ]);

        // Descending order
        $response = $this->get(route('episodes.index', ['sort' => 'imdb_score', 'direction' => 'desc']));
        $response->assertSeeInOrder([$episode2->title, $episode1->title]);

        // Ascending order
        $response = $this->get(route('episodes.index', ['sort' => 'imdb_score', 'direction' => 'asc']));
        $response->assertSeeInOrder([$episode1->title, $episode2->title]);
    }

    public function test_can_sort_episodes_by_user_score(): void
    {
        $part = Part::factory()->create();
        $episode1 = Episode::factory()->create(['part_id' => $part->id]);
        $episode2 = Episode::factory()->create(['part_id' => $part->id]);

        $user = User::factory()->create();

        // episode1 avg rating: 5
        Rating::create(['user_id' => $user->id, 'episode_id' => $episode1->id, 'rating' => 5]);

        // episode2 avg rating: 10
        Rating::create(['user_id' => $user->id, 'episode_id' => $episode2->id, 'rating' => 10]);

        // Descending order
        $response = $this->get(route('episodes.index', ['sort' => 'ratings_avg_rating', 'direction' => 'desc']));
        $response->assertSeeInOrder([$episode2->title, $episode1->title]);

        // Ascending order
        $response = $this->get(route('episodes.index', ['sort' => 'ratings_avg_rating', 'direction' => 'asc']));
        $response->assertSeeInOrder([$episode1->title, $episode2->title]);
    }
}
