<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Models\RecipeAuthor;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ResetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncates the tables related to the recipes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schema::disableForeignKeyConstraints();

        // Truncate the Recipe Ingredients table
        RecipeIngredient::truncate();

        // Truncate the Recipe Steps
        RecipeStep::truncate();

        // Truncate the Recipe table
        Recipe::truncate();

        // Truncate the Recipe Authors table
        RecipeAuthor::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
