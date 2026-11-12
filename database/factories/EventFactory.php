<?php

namespace Database\Factories;

use App\Models\Event;
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
            'title' => fake()->unique()->words(3, true),
            'type' => 'HIMA',
            'id_prodi' => null,
        ];
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
