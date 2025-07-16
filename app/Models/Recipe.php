<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'cooking_time',
        'thumbnail'
    ];

    protected $appends = ['thumbnail_url'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(RecipeImage::class)->orderBy('order');
    }

    public function steps()
    {
        return $this->hasMany(RecipeStep::class)->orderBy('step_number');
    }

    public function alats()
    {
        return $this->belongsToMany(Alat::class, 'recipe_alats')->withPivot('amount')->withTimestamps();
    }

    public function bahans()
    {
        return $this->belongsToMany(Bahan::class, 'recipe_bahans')->withPivot('amount')->withTimestamps();
    }

    public function favorites()
    {
        return $this->hasMany(RecipeFavorite::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'recipe_favorites')->withTimestamps();
    }

    // Accessors
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('images/recipes/' . $this->thumbnail);
        }
        return asset('images/recipes/default-recipe.jpg');
    }

    // Check if user has favorited this recipe
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
