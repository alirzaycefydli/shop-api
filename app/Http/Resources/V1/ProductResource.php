<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'brand' => $this->brand,
            'price' => $this->price,
            'discounted_price'=>$this->discountedPrice,
            'quantity' => $this->quantity,
            'discount_percent' => $this->discount_percent,
            'is_featured' => $this->is_featured,
            'primary_image' => $this->primaryImage ? $this->primaryImage->image_path : null,
            'images' => $this->images ? $this->images->pluck('image_path') : null,
            'reviews' => $this->reviews ? $this->reviews->select('rating', 'review', 'title', 'created_at') : null,
        ];
    }
}
