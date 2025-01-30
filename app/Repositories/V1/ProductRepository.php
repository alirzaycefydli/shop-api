<?php

namespace App\Repositories\V1;

use App\Models\V1\Product;

class ProductRepository
{
    /**
     * Returns the latest products
     * @return mixed
     */
    public function newArrivalProducts(): mixed
    {
        return Product::where('is_confirmed', 1)
            ->where('quantity', '>', 0)
            ->with('primaryImage')
            ->latest()
            ->take(4)
            ->get();
    }

    public function findById(int $id)
    {
        return Product::where('id', $id)
            ->where('is_confirmed', 1)
            ->where('quantity', '>', 0)
            ->with('images')
            ->firstOrFail();
    }
}
