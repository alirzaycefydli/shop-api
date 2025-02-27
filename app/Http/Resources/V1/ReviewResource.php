<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'rating' => $this->rating,
            'review' => $this->review,
            'title' => $this->title,
            'created_at' => $this->created_at
        ];
    }
}
