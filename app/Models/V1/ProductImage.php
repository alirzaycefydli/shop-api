<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path', 'is_primary'];
}
