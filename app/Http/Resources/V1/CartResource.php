<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\ProductResource;

class CartResource extends JsonResource
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
            'discounted_price' => $this->discounted_price,
            'quantity' => $this->quantity,
            'stock' => $this->stock,
            'discount_percent' => $this->discount_percent,
            'primary_image' => $this->primaryImage ? $this->primaryImage->image_path : null,
        ];
    }
}
