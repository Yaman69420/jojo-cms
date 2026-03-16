<?php

namespace Database\Factories;

use App\Models\Episode;
use App\Models\Part;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'part_id' => Part::factory(),
            'title' => $this->faker->sentence(4),
            'episode_number' => $this->faker->numberBetween(1, 24),
            'release_date' => $this->faker->date(),
            'imdb_score' => $this->faker->randomFloat(1, 1, 10),
            'summary' => $this->faker->paragraph(),
        ];
    }
}
