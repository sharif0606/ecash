<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductDetail;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\DoseDescription;
use App\Models\MedicineList;

class Product extends Model
{
    public function brand(){
        return $this->belongsTo(Brand::class, 'brandId');
    }

    public function categories(){
        return $this->belongsTo(Category::class, 'categoryId');
    }

    public function stocks(){
        return $this->hasMany(Stock::class, 'productId');
    }
}
