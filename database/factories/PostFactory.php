<?php

namespace Database\Factories;

use App\Models\Category;
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
        return [
            'category_id' => Category::all()->random()->id,
            'title' => $this->faker->name,
            'author' => $this->faker->name,
            'user' => $this->faker->userName,
            'image' => $this->faker->imageUrl,
            'content' => $this->faker->paragraphs(5, true),
            'tags' => implode(',', $this->faker->words(5)),
            'status' => $this->faker->randomElement([0, 1]),
            'comments_count' => $this->faker->numberBetween(0, 100),
            'views_count' => $this->faker->numberBetween(0, 1000),
            'likes_count' => $this->faker->numberBetween(0, 500),
        ];
    }
}
