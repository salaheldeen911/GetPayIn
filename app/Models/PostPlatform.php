<?php

namespace App\Models;

use App\Enums\PlatformStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPlatform extends Model
{
    use HasFactory;

    protected $table = 'post_platform';

    protected $fillable = [
        'post_id',
        'platform_id',
        'platform_status',
        'error_message',
        'platform_post_id',
    ];

    protected $casts = [
        'platform_status' => PlatformStatus::class,
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
