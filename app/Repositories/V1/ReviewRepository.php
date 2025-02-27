<?php

namespace App\Repositories\V1;

use App\Models\V1\Review;

class ReviewRepository
{
    public function getReviewByProduct($product)
    {
        return $product->reviews()->orderBy('created_at', 'desc')->get();
    }

    public function createReview($request)
    {
        $existing_review = Review::where('product_id', $request->product_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existing_review) {
            throw new \Exception("Review already exists");
        }

        return Review::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'title' => $request->title,
        ]);
    }

}
