<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->content,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'status' => $this->status?->value ?? $this->status,
            'is_featured' => (bool) $this->is_featured,
            'view_count' => (int) $this->view_count,
            'published_at' => $this->published_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'avatar' => $this->user?->avatar ? Storage::disk('public')->url($this->user->avatar) : null,
            ],
        ];
    }
}
