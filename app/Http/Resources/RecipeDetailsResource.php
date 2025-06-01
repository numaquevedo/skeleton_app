<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeDetailsResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->resource['status'] ?? 'success',
            'name' => $this->resource['name'] ?? '',
            'description' => $this->resource['description'] ?? '',
            'steps' => $this->resource['steps'] ?? [],
            'ingredients' => $this->resource['ingredients'] ?? [],
            'author' => [
                'name' => $this->resource['author']['name'] ?? '',
                'email' => $this->resource['author']['email'] ?? '',
                'gravatar' => $this->resource['author']['gravatar'] ?? '',
            ],
        ];
    }
}
