<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionAnswerResource extends JsonResource
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
            'body' => $this->body,
            'status' => $this->status?->value ?? $this->status,
            'answered_by' => $this->user?->name ?? 'Admin Layanan',
            'answered_at' => $this->answered_at?->toISOString() ?? $this->created_at?->toISOString(),
        ];
    }
}
