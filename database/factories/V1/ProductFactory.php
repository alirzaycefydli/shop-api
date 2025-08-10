<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'slug' => fake()->slug(),
            'description' => fake()->sentence(),
            'short_description' => fake()->sentence(2, true),
            'price' => fake()->randomFloat(2, 1, 100),
            'brand' => fake()->words(2, true),
            'quantity' => fake()->randomNumber(1,100),
            'discount_percent' => fake()->randomNumber(1,20),
            'is_confirmed' => true,
            'is_featured' => true,
        ];
    }
}
