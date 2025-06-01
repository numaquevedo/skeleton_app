<?php

namespace Tests\Feature;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RecipeDetailsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('app:seed-data --authors=2 --recipes=20 --ingredients=10 --steps=12');
    }

    /**
     * A basic feature test example.
     */
    public function test_that_a_recipe_is_retrieved(): void
    {
        // Get the slug of a random recipe.
        $recipe = Recipe::select('slug', 'name')->orderByRaw('RAND()')->first();

        $this->assertNotEmpty($recipe->slug);

        $response = $this->get('/api/recipes/' . urlencode($recipe->slug));

        $response->assertStatus(200);

        $this->assertEquals($recipe->name, $response->json('name'));
    }

    public function test_that_a_recipe_does_not_exist(): void
    {
        $response = $this->get('/api/recipes/some-random-slug-that-will-never-exist');

        $response->assertStatus(200);

        $this->assertEmpty($response->json('name'));
    }
}
