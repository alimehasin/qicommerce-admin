<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ["name", "description", "price", "stock", "image_path", "sku", "status"];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
