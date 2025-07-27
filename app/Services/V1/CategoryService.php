<?php

namespace App\Services\V1;

use App\Models\V1\Category;
use App\Models\V1\Product;

class CategoryRepository
{

    public function getAllCategories()
    {
        return Category::whereNull('parent_category_id')
            ->with('subcategories')
            ->get();
    }

    /**
     * Returns the products under the category/sub-category or all products if no category selected
     * */
    public function getProductsByCategory($category, $request)
    {
        $sort_by = $request->get('sortBy', 'default'); // Default sorting is 'default'

        if ($category && $category->exists) {
            $query = Product::whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id)
                    ->orWhere('categories.parent_category_id', $category->id);
            })->with(['primaryImage','reviews']);
        } else {
            $query = Product::with('primaryImage','reviews');
        }

        $query = $this->sortProducts($query, $sort_by);

        return $query->paginate(12);
    }

    /**
     * Sorts the products given
     * */
    public function sortProducts($query, $sort_by)
    {
        switch ($sort_by) {
            case 'name_asc':
                $query->orderBy('title');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price_asc':
                $query->orderByRaw('price - (price * discount_percent / 100) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('price - (price * discount_percent / 100) DESC');
                break;
            case 'discount':
                $query->orderBy('discount_percent', 'desc');
                break;
            case 'default':
                $query->orderBy('updated_at', 'desc');
                break;
            default: // Default sorting
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

}
