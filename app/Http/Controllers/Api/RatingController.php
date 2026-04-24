<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Get all ratings for a product
     */
    public function getProductRatings($productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        $ratings = $product->ratings()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $ratings,
            'average_rating' => $product->average_rating,
            'rating_count' => $product->rating_count
        ]);
    }

    /**
     * Submit or update a rating
     */
    public function submitRating(Request $request, $productId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::findOrFail($productId);
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to submit a rating'
            ], 401);
        }

        // Check if user has purchased this product
        $hasPurchased = $user->orders()
            ->whereHas('items', fn ($q) => $q->where('product_id', $productId))
            ->where('status', 'completed')
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'You can only rate products you have purchased'
            ], 403);
        }

        // Create or update rating
        $rating = Rating::updateOrCreate(
            [
                'product_id' => $productId,
                'user_id' => $user->id
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully',
            'data' => $rating,
            'average_rating' => $product->average_rating,
            'rating_count' => $product->rating_count
        ]);
    }

    /**
     * Get user's rating for a product
     */
    public function getUserRating($productId): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $rating = Rating::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $rating
        ]);
    }

    /**
     * Delete a rating
     */
    public function deleteRating($productId): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $rating = Rating::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $rating->delete();

        $product = Product::find($productId);

        return response()->json([
            'success' => true,
            'message' => 'Rating deleted successfully',
            'average_rating' => $product->average_rating,
            'rating_count' => $product->rating_count
        ]);
    }
}
