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
        return [
            'name' => $this->faker->name(),
            'emp_id' => 'EMP-' . fake()->unique()->numerify('#####'),
            'email' => $this->faker->email(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password123'),
            'role' => $this->faker->randomElement(['admin', 'owner', 'pharmacist', 'cashier']),
            'shift' => $this->faker->randomElement(['pagi', 'siang', 'malam']),
            'date_of_birth' => $this->faker->date(),
            'alamat' => $this->faker->address(),
            'salary' => $this->faker->numberBetween(3000000, 8000000),
            'start_date' => $this->faker->date(),
            'remember_token' => Str::random(10),
        ];
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
