<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
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
            'discounted_price'=> $this->discounted_price,
            'discount_percent' => $this->discount_percent,
            'rating' => $this->reviews->avg('rating'),
            'image'=>$this->primaryImage ? $this->primaryImage->image_path : null,
        ];
    }
}
