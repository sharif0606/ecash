<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function product(){
        return $this->belongsTo(Product::class, 'item_id');
    }
}
