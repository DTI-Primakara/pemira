<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Candidate>
 */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name_candidates' => fake()->name(),
            'number' => fake()->numberBetween(1, 5),
            'vision' => fake()->sentence(),
            'mission' => fake()->sentence(),
            'position' => 'Calon Ketua',
            'image' => 'paslon-images/placeholder.png',
        ];
    }
}
