<?php

namespace Database\Factories;

use App\Models\RecipeAuthor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeAuthor>
 */
class RecipeAuthorFactory extends Factory
{
    protected $model = RecipeAuthor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $email = fake()->unique()->safeEmail();
        return [
            'name' => fake()->name(),
            'email' => $email,
            'gravatar' => 'https://gravatar.com/avatar/' . md5($email),
        ];
    }
}
