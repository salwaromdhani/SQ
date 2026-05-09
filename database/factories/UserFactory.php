<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Password cache (évite recalcul Hash inutile)
     */
    protected static ?string $password = null;

    /**
     * Default model state
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => false,
        ];
    }

    /**
     * User not verified
     */
    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Admin user state (important pour ton projet SmartQueue)
     */
    public function admin(): static
    {
        return $this->state(fn () => [
            'is_admin' => true,
        ]);
    }

    /**
     * Regular user state
     */
    public function user(): static
    {
        return $this->state(fn () => [
            'is_admin' => false,
        ]);
    }
}