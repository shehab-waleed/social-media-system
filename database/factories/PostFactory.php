<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all();

        return [
            'title' => fake()->title,
            'body' => fake()->paragraph(),
            'user_id' => $user->random()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
