<?php

namespace Database\Factories;

use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prodi>
 */
class ProdiFactory extends Factory
{
    protected $model = Prodi::class;

    public function definition(): array
    {
        return [
            'id_prodi' => fake()->unique()->numerify('0#0#'),
            'name' => fake()->words(2, true),
            'short_name' => fake()->lexify('???'),
            'faculty' => 'Fakultas Teknologi Informasi dan Desain',
            'faculty_short' => 'FTID',
        ];
    }
}
