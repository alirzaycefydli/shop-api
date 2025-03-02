<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\V1\CartFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity'];
}
