<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::query()
            ->with(['category', 'user'])
            ->published()
            ->latest('published_at');

        if ($request->filled('category')) {
            $category = $request->input('category');
            $query->whereHas('category', function ($q) use ($category) {
                if (is_numeric($category)) {
                    $q->where('id', $category);
                } else {
                    $q->where('slug', $category);
                }
            });
        }

        if ($request->filled('search')) {
            $search = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                    ->orWhere('summary', 'like', $search)
                    ->orWhere('content', 'like', $search);
            });
        }

        $perPage = min(max((int) $request->input('per_page', 10), 1), 50);

        return PostResource::collection($query->paginate($perPage));
    }

    public function featured(Request $request): AnonymousResourceCollection
    {
        $limit = min(max((int) $request->input('limit', 5), 1), 20);

        $posts = Post::query()
            ->with(['category', 'user'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->limit($limit)
            ->get();

        return PostResource::collection($posts);
    }

    public function show(string $slug): JsonResponse
    {
        $post = Post::query()
            ->with(['category', 'user'])
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $post) {
            return response()->json(['message' => 'Artikel tidak ditemukan.'], 404);
        }

        $post->increment('view_count');

        return response()->json([
            'data' => new PostResource($post),
        ]);
    }
}
