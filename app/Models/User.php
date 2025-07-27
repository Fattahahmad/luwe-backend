<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the profile picture URL
     */
    public function getProfilePictureUrlAttribute(): string
    {
        // Use request URL for dynamic base URL (works with ngrok)
        $baseUrl = request()->getSchemeAndHttpHost();

        if ($this->profile_picture) {
            return $baseUrl . '/api/image/' . $this->profile_picture;
        }

        return $baseUrl . '/api/image/default.svg';
    }

    /**
     * Get the profile picture path for storage
     */
    public function getProfilePicturePath(): string
    {
        return $this->profile_picture ?? 'default.svg';
    }

    // Recipe relationships
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function favoriteRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_favorites')->withTimestamps();
    }

    public function recipeFavorites()
    {
        return $this->hasMany(RecipeFavorite::class);
    }

    // Notification relationships
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'from_user_id');
    }
}
