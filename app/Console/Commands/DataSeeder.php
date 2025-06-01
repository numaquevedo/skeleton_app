<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Models\RecipeAuthor;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use Illuminate\Console\Command;

class DataSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-data {--authors=2} {--recipes=4} {--steps=3} {--ingredients=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create data in the tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Lets validate the options
        $numberOfAuthors = $this->getSafeValueFromOption('authors', 2);
        $numberOfRecipes = $this->getSafeValueFromOption('recipes', 4);
        $numberOfRecipeSteps = $this->getSafeValueFromOption('steps', 3);
        $numberOfRecipeIngredients = $this->getSafeValueFromOption('ingredients', 5);

        // Create the recipe authors along with some recipes, steps and ingredients
        RecipeAuthor::factory()
            ->count($numberOfAuthors)
            ->has(
                Recipe::factory()->count($numberOfRecipes)
                    ->has(RecipeIngredient::factory()->count($numberOfRecipeIngredients), 'ingredients')
                    ->has(RecipeStep::factory()->count($numberOfRecipeSteps), 'steps')
            )
            ->create();
    }

    public function getSafeValueFromOption(string $optionName, int $defaultValue)
    {
        if(!is_numeric($this->option($optionName))) {
            return $defaultValue;
        }

        return $this->option($optionName);
    }
}
