<?php

namespace App\Services\V1;

use App\Models\V1\Product;
use App\Models\V1\Wishlist;

class WishlistRepository
{

    public function getWishlist()
    {
        $wishlistItems = Wishlist::where('user_id', auth('sanctum')->user()->id)->get(['product_id']);

        return Product::whereIn('id', $wishlistItems)
            ->with('primaryImage')
            ->with('reviews')
            ->get();
    }

    public function addToWishlist($request)
    {
        Wishlist::firstOrCreate(
            [
                'user_id' => auth('sanctum')->user()->id,
                'product_id' => $request->input('product_id'),
            ],
        );

        return null;
    }

    public function removeFromWishlist($product)
    {
        $wishlistItem = Wishlist::where('product_id', $product->id)
            ->where('user_id', auth('sanctum')->user()->id)
            ->first();

        if (!$wishlistItem) {
            throw new \Exception('Product not found!', 404);
        }

        $wishlistItem->delete();

        return null;
    }

}
