<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryProductResource extends JsonResource
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
            'short_description' => $this->short_description,
            'brand' => $this->brand,
            'price' => $this->price,
            'discounted_price'=>$this->discountedPrice,
            'discount_percent' => $this->discount_percent,
            'primary_image' => $this->primaryImage ? $this->primaryImage->image_path : null,
            'rating' => $this->reviews->avg('rating'),
        ];
    }
}
