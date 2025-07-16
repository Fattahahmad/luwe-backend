<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'image_path',
        'order'
    ];

    protected $appends = ['image_url'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function getImageUrlAttribute()
    {
        return asset('images/recipes/' . $this->image_path);
    }
}
