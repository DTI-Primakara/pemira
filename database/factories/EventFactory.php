<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            // Falls back to the period being run now, which the base migration
            // always creates; tests that care pass their own.
            'period_id' => fn () => Period::activeId() ?? Period::factory()->active(),
            'title' => fake()->unique()->words(3, true),
            'type' => 'HIMA',
            'id_prodi' => null,
        ];
    }

    public function forPeriod(Period $period): static
    {
        return $this->state(fn () => ['period_id' => $period->id]);
    }

    public function universityWide(): static
    {
        return $this->state(fn () => ['type' => 'BEM', 'id_prodi' => null]);
    }

    public function forProdi(string $prodiCode): static
    {
        return $this->state(fn () => ['type' => 'HIMA', 'id_prodi' => $prodiCode]);
    }
}
