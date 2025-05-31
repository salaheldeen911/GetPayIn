<?php

namespace App\Models;

use App\Enums\PlatformType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'requirements',
    ];

    protected $casts = [
        'type' => PlatformType::class,
        'requirements' => 'array',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->withPivot(['platform_status', 'platform_post_id', 'error_message'])
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_platform')
            ->withPivot(['access_token', 'refresh_token', 'token_expires_at', 'is_active'])
            ->withTimestamps();
    }

    public function getRequirement(string $name)
    {
        return $this->requirements[$name] ?? null;
    }

    public function validateContent(string $content): bool
    {
        $maxLength = (int) $this->getRequirement('max_length');

        return strlen($content) <= $maxLength;
    }

    public function getMaxLength(): int
    {
        return (int) $this->getRequirement('max_length') ?? $this->type->getMaxLength();
    }

    public function getDisplayName(): string
    {
        return $this->type->getDisplayName();
    }
}
