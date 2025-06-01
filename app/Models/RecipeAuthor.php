<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeAuthor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'gravatar'
    ];

    protected $table = 'recipe_authors';

    /**
     * Get the recipes that belong to the author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'author_id', 'id');
    }
}
