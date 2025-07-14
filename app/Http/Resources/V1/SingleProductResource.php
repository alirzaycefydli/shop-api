<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
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
            'image'=>$this->primaryImage ? $this->primaryImage->image_path : null,
            'short_description' => $this->short_description,
            'brand' => $this->brand,
            'price' => $this->price,
            'rating' => $this->reviews->avg('rating'),
            'discounted_price'=> $this->discounted_price,
            'discount_percent' => $this->discount_percent,
            'is_featured' => $this->is_featured,
            'reviews' => $this->reviews ? $this->reviews->select('rating') : null,
        ];
    }
}
