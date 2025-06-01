<?php

namespace Tests\Feature;

use App\Models\RecipeAuthor;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RecipeListTest extends TestCase
{
    //use RefreshDatabase;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('app:seed-data --authors=2 --recipes=2 --ingredients=2 --steps=2');
    }

    /**
     * A basic feature test example.
     */
    public function test_that_the_recipes_have_the_desired_structure(): void
    {
        $response = $this->get('/api/recipes');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'recipes' => [
                '*' => [
                    'id',
                    'name',
                    'author_id',
                    'slug',
                    'author' => [
                        'id',
                        'name',
                        'email',
                        'gravatar'
                    ]
                ]
            ]
        ]);
    }

    public function test_searching_by_email()
    {
        // Let's get one of the authors email
        $authorDetails = RecipeAuthor::select('email', 'id')->limit(1)->first();

        // Assert that we got an email
        $this->assertNotEmpty($authorDetails->email);

        // Call the endpoint and ensure that only the recipes that belong to the author are returned.
        $response = $this->get('/api/recipes?email=' . urlencode($authorDetails->email));

        $response->assertStatus(200);

        // Get the recipes and ensure that we got some records.
        $recipes = $response->json('recipes');
        $this->assertNotEmpty($recipes);

        foreach($recipes as $recipe) {
            $this->assertEquals($recipe['author_id'], $authorDetails->id);
        }
    }

    public function test_searching_by_keyword()
    {
        // I am just gonna get an ingredient of a particular record and extract a word.
        $step = RecipeStep::with(['recipe'])->first();

        $this->assertNotEmpty($step->step);

        $words = explode(" ", $step->step);

        $randomIndex = rand(0, count($words) - 1);

        $word = $words[$randomIndex];

        // Hit the endpoint with the keyword.
        $response = $this->get('/api/recipes?keyword=' . urlencode($word));

        // Assert that at least one recipe exists
        $this->assertGreaterThan(0, $response->json('recipes'));
    }

    public function test_searching_by_wrong_keyword()
    {
        $response = $this->get('/api/recipes?keyword=somethingreallyspecificthatwillneverexist');

        // Assert that at least one recipe exists
        $this->assertCount(0, $response->json('recipes'));
    }

    public function test_searching_by_ingredient()
    {
        $ingredient = RecipeIngredient::with(['recipe'])->first();

        $this->assertNotEmpty($ingredient->ingredient_details);

        $words = explode(" ", $ingredient->ingredient_details);

        $randomIndex = rand(0, count($words) - 1);

        $word = $words[$randomIndex];

        $response = $this->get('/api/recipes?ingredient=' . urlencode($word));

        $this->assertGreaterThan(0, $response->json('recipes'));
    }

    public function test_searching_by_all_params_correctly()
    {
        $author = RecipeAuthor::with(['recipes', 'recipes.ingredients', 'recipes.steps'])->first();

        // Get the email
        $authorEmail = $author->email;
        $ingredientsWords = explode(' ', $author->recipes[0]->ingredients[0]->ingredient_details);
        $randomIngredient = $ingredientsWords[rand(0, count($ingredientsWords) - 1)];
        $keywords = explode(' ', $author->recipes[0]->steps[0]->step);
        $randomKeyword = $keywords[rand(0, count($keywords) - 1)];

        $urlStringParams = [
            'email' => $authorEmail,
            'keyword' => $randomKeyword,
            'ingredient' => $randomIngredient
        ];

        // Call the endpoint
        $response = $this->get('/api/recipes?' . http_build_query($urlStringParams));

        $response->assertStatus(200);

        // Should have at least one.
        $this->assertGreaterThan(0, $response->json('recipes'));
    }
}
