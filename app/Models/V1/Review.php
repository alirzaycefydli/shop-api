<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\V1\ReviewFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'rating', 'review', 'title'];
}
