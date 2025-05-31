<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image_url',
        'scheduled_at',
        'status',
        'user_id',
        'publish_error',
        'platform_specific_data',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'platform_specific_data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if ($post->image_url) {
                ImageHelper::delete($post->image_url);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'post_platform')
            ->withPivot(['platform_status', 'error_message', 'platform_post_id'])
            ->withTimestamps();
    }

    public function scopeDraft($query)
    {
        return $query->where('status', PostStatus::DRAFT->value);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', PostStatus::SCHEDULED->value);
    }

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::PUBLISHED->value);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', PostStatus::FAILED->value);
    }

    public function scopeDue($query)
    {
        return $query->where('status', PostStatus::SCHEDULED)
            ->where('scheduled_at', '<=', now());
    }

    public function isPublishable(): bool
    {
        return $this->status === PostStatus::SCHEDULED &&
            $this->scheduled_at <= now();
    }

    public function schedule($scheduledAt)
    {
        $this->update([
            'status' => PostStatus::SCHEDULED,
            'scheduled_at' => $scheduledAt,
        ]);
    }

    public function markAsPublished()
    {
        $this->update([
            'status' => PostStatus::PUBLISHED,
            'publish_error' => null,
        ]);
    }

    public function markAsFailed($error)
    {
        $this->update([
            'status' => PostStatus::FAILED,
            'publish_error' => $error,
        ]);
    }

    public function hasMedia(): bool
    {
        return ! empty($this->image_url);
    }
}
