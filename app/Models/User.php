<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'user_platform')
            ->withPivot(['access_token', 'refresh_token', 'token_expires_at', 'is_active'])
            ->withTimestamps();
    }

    public function activePlatforms(): BelongsToMany
    {
        return $this->platforms()->wherePivot('is_active', true);
    }

    public function hasValidTokenFor(Platform $platform): bool
    {
        $platformUser = $this->platforms()
            ->where('platform_id', $platform->id)
            ->wherePivot('is_active', true)
            ->first();

        if (! $platformUser) {
            return false;
        }

        return $platformUser->pivot->token_expires_at > now();
    }
}
