<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    /**
     * Get user's favorite products
     */
    public function index(Request $request): JsonResponse
    {
        $favorites = $request->user()->favorites()
            ->with('product')
            ->get()
            ->pluck('product');

        return response()->json($favorites);
    }

    /**
     * Toggle like/favorite for a product
     */
    public function toggle(Request $request, Product $product): JsonResponse
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'message' => 'Removed from favorites',
                'is_liked' => false
            ]);
        }

        Favorite::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id
        ]);

        return response()->json([
            'message' => 'Added to favorites',
            'is_liked' => true
        ], 201);
    }

    /**
     * Check if product is liked by user
     */
    public function check(Request $request, Product $product): JsonResponse
    {
        $isLiked = Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->exists();

        return response()->json([
            'is_liked' => $isLiked,
            'likes_count' => $product->favorites()->count()
        ]);
    }

    /**
     * Get likes count for a product
     */
    public function count(Product $product): JsonResponse
    {
        return response()->json([
            'product_id' => $product->id,
            'likes_count' => $product->favorites()->count()
        ]);
    }

    /**
     * Delete a favorite
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return response()->json(null, 204);
    }
}
