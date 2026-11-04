<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'name' => $this->maskName($this->name),
            'subject' => $this->subject,
            'body' => $this->body,
            'status' => $this->status?->value ?? $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'topic' => new QuestionTopicResource($this->whenLoaded('topic')),
            'answers' => QuestionAnswerResource::collection($this->whenLoaded('answers')),
            'latest_answer' => new QuestionAnswerResource($this->whenLoaded('latestAnswer')),
        ];
    }

    private function maskName(string $name): string
    {
        $words = explode(' ', trim($name));
        $masked = array_map(function ($word) {
            $len = mb_strlen($word);
            if ($len <= 2) {
                return $word;
            }

            return mb_substr($word, 0, 1).str_repeat('*', min(4, $len - 1));
        }, $words);

        return implode(' ', $masked);
    }
}
