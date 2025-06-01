<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->string('name')->comment('The name of the recipe');
            $table->text('description')->comment('The description of the recipe');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Add constraints
            $table
                ->foreign('author_id')
                ->references('id')
                ->on('recipe_authors')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            // Add indexes
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do nothing
    }
};
