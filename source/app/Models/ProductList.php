<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    public function brand(){
        return $this->belongsTo(Brand::class, 'brandId');
    }

    public function categories(){
        return $this->belongsTo(Category::class, 'categoryId');
    }
}
