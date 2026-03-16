<?php

namespace Database\Factories;

use App\Models\Part;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Part>
 */
class PartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'number' => $this->faker->unique()->numberBetween(1, 10),
            'release_year' => $this->faker->year(),
            'summary' => $this->faker->paragraph(),
        ];
    }
}
