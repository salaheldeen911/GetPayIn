<?php

namespace App\Enums;

enum PlatformStatus: string
{
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case FAILED = 'failed';
}
