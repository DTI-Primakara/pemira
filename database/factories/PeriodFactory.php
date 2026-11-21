<?php

namespace Database\Factories;

use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Period>
 */
class PeriodFactory extends Factory
{
    protected $model = Period::class;

    public function definition(): array
    {
        return [
            'name' => 'PEMIRA '.fake()->unique()->numberBetween(2000, 2099),
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'is_vote_count_visible' => false,
            'is_active' => false,
        ];
    }

    /** The one period everything public reads from. */
    public function active(): static
    {
        return $this->afterCreating(fn (Period $period) => $period->activate());
    }

    public function closed(): static
    {
        return $this->state(fn () => [
            'starts_at' => now()->subDays(5),
            'ends_at' => now()->subDays(4),
        ]);
    }
}
