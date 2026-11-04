<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionTopicResource;
use App\Models\QuestionTopic;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionTopicController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $topics = QuestionTopic::query()
            ->active()
            ->withCount([
                'questions' => fn ($q) => $q->answered(),
                'faqs' => fn ($q) => $q->active(),
            ])
            ->get();

        return QuestionTopicResource::collection($topics);
    }
}
