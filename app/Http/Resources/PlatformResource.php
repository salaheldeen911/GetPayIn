<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'requirements' => $this->requirements,
            'pivot' => $this->when($this->pivot, [
                'platform_status' => $this->pivot?->platform_status,
                'error_message' => $this->pivot?->error_message,
                'platform_post_id' => $this->pivot?->platform_post_id,
                'created_at' => $this->pivot?->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $this->pivot?->updated_at?->format('Y-m-d H:i:s'),
            ]),
        ];
    }
}
