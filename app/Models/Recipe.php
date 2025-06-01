<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id', 'name', 'description'
    ];

    protected $table = 'recipes';

    /**
     * Retrieve the author that owns this recipe.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(RecipeAuthor::class, 'author_id');
    }

    /**
     * Get the ingredients associated with the recipe.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class, 'recipe_id');
    }

    /**
     * Get the preparation steps associated with the recipe.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps()
    {
        return $this->hasMany(RecipeStep::class, 'recipe_id');
    }
}
