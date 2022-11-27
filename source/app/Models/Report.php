<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public function product()
    {
        return $this->hasOne(Product::class,'productId','id');
    }
}
