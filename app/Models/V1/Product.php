<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\V1\ProductFactory> */
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'short_description', 'brand', 'price', 'quantity', 'discount_percent', 'is_confirmed', 'is_featured'];

    /**
     * Get the product images
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderByDesc('is_primary');
    }

    /**
     * Get the primary image of the product
     *
     * @return HasOne
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the reviews of the product
     *
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    /**
     * Calculate the price in cents
     *
     * @return Attribute
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100,
        )->withoutObjectCaching();
    }

    /**
     * Calculate the discounted price of the product
     *
     * @return Attribute
     */
    public function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->price * (1 - ($this->discount_percent / 100)),
        );
    }
}
