<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->ensureRolesExist();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'nim' => fake()->unique()->numerify('23010#####'),
            'roles' => \App\Models\User::ROLE_PARTICIPANT,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'roles' => \App\Models\User::ROLE_ADMIN,
        ]);
    }

    /**
     * Give the voter a student number belonging to a specific study programme.
     */
    public function inProdi(string $prodiCode): static
    {
        return $this->state(fn (array $attributes) => [
            'nim' => '23'.$prodiCode.fake()->unique()->numerify('####'),
        ]);
    }

    /**
     * users.roles is a foreign key, so the lookup rows must exist before any
     * user is created in a freshly migrated test database.
     */
    protected function ensureRolesExist(): void
    {
        foreach ([1 => 'Super Admin', 2 => 'Admin', 3 => 'Peserta'] as $id => $name) {
            \App\Models\Role::firstOrCreate(['id_roles' => $id], ['nama_roles' => $name]);
        }
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
