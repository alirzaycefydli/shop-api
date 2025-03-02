<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /** @use HasFactory<\Database\Factories\V1\WishlistFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'product_id'];
}
