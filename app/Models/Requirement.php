<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformRequirement extends Model
{
    use HasFactory;

    protected $table = 'platform_requirements';

    protected $fillable = [
        'platform_id',
        'name',
        'type',
        'value',
        'description',
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_platform')
            ->withPivot(['access_token', 'refresh_token', 'token_expires_at', 'is_active'])
            ->withTimestamps();
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(PlatformRequirement::class);
    }

    public function getRequirement(string $name)
    {
        $requirement = $this->requirements()
            ->where('name', $name)
            ->first();

        return $requirement ? $requirement->value : null;
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

    public function getRequirements(): array
    {
        return $this->requirements()->get()->map(function ($requirement) {
            return [
                'name' => $requirement->name,
                'value' => $requirement->value,
                'type' => $requirement->type,
            ];
        })->all();
    }

    public function getDisplayName(): string
    {
        return $this->type->getDisplayName();
    }
}
