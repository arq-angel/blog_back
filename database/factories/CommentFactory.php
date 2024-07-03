<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => Post::all()->random()->id,
            'author' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'content' => $this->faker->paragraph,
            'status' => $this->faker->randomElement([0, 1, 2]),
        ];
    }
}
