<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeAuthor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->text(25);
        return [
            'name' => $name,
            'description' => fake()->text(),
            'slug' => Str::slug($name . Str::random(5)),
        ];
    }
}
