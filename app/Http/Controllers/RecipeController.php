<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRecipesListRequest;
use App\Http\Resources\RecipeDetailsResource;
use App\Http\Resources\RecipeResponseResource;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(GetRecipesListRequest $request): RecipeResponseResource
    {
        // Build the base query.
        $recipesQuery = Recipe::with(['author' => function ($query) {
            $query->select('id', 'name', 'email', 'gravatar');
        }])
            ->select('id', 'name', 'author_id', 'slug');

        // Filter by author email if we received it in the query.
        if ($request->filled('email')) {
            $recipesQuery->whereHas('author', function ($query) use ($request) {
                $query->where('email', $request->get('email'))->select('id');
            });
        }

        // Filter by keyword
        if ($request->filled('keyword')) {
            $recipesQuery->where(function ($keywordQuery) use ($request) {
                $keywordQuery->where('name', 'like', '%' . $request->get('keyword') . '%')
                    ->orWhere('description', 'like', '%' . $request->get('keyword') . '%')
                    ->orWhereHas('ingredients', function ($query) use ($request) {
                        $query->where('ingredient_details', 'like', '%' . $request->get('keyword') . '%')
                            ->select('id');
                    })
                    ->orWhereHas('steps', function ($query) use ($request) {
                        $query->where('step', 'like', '%' . $request->get('keyword') . '%')
                            ->select('id');
                    });
            });
        }

        // Filter by Ingredient
        if ($request->filled('ingredient')) {
            $recipesQuery->whereHas('ingredients', function ($query) use ($request) {
                $query->where('ingredient_details', 'like', '%' . $request->get('ingredient') . '%')
                    ->select('id');
            });
        }

        $recipesList = $recipesQuery->get();

        if ($recipesList->isEmpty()) {
            return new RecipeResponseResource([]);
        }

        // Return the recipes
        return new RecipeResponseResource(['recipes' => $recipesList]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Default response
        return new RecipeResponseResource(['status' => 'not available']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): RecipeDetailsResource
    {
        // Get the details of the recipe
        $recipeDetails = Recipe::with(['author' => function ($query) {
            $query->select('id', 'name', 'email', 'gravatar');
        }])
            ->select('id', 'author_id', 'name', 'description')
            ->where('slug', $slug)
            ->first();

        if (empty($recipeDetails)) {
            return new RecipeDetailsResource([]);
        }

        // Return the details of the recipe.
        return new RecipeDetailsResource([
            'name' => $recipeDetails->name,
            'description' => $recipeDetails->description,
            'author' => [
                'name' => $recipeDetails->author->name,
                'email' => $recipeDetails->author->email,
                'gravatar' => $recipeDetails->author->gravatar,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
