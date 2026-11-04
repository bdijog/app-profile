<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function show(string $location): JsonResponse
    {
        $menu = Menu::query()
            ->where('is_active', true)
            ->where('location', $location)
            ->with(['items' => function ($q) {
                $q->where('is_active', true)
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->with(['children' => fn ($cq) => $cq->where('is_active', true)->orderBy('sort_order')]);
            }])
            ->first();

        if (! $menu) {
            return response()->json(['message' => 'Menu tidak ditemukan.'], 404);
        }

        return response()->json([
            'data' => new MenuResource($menu),
        ]);
    }
}
