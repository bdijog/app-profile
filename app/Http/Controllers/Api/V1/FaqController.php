<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FaqController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Faq::query()
            ->with('topic')
            ->active();

        if ($request->filled('topic')) {
            $topic = $request->input('topic');
            $query->whereHas('topic', function ($q) use ($topic) {
                if (is_numeric($topic)) {
                    $q->where('id', $topic);
                } else {
                    $q->where('slug', $topic);
                }
            });
        }

        if ($request->filled('search')) {
            $search = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', $search)
                    ->orWhere('answer', 'like', $search);
            });
        }

        return FaqResource::collection($query->get());
    }
}
