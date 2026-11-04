<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $page) {
            return response()->json(['message' => 'Halaman tidak ditemukan.'], 404);
        }

        return response()->json([
            'data' => new PageResource($page),
        ]);
    }
}
