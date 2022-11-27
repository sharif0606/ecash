<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    public function stock(){
        return $this->hasMany(Stock::class,'id','batchId');
    }
    
	public function batch(){
        return $this->hasOne(Stock::class, 'batchId','batchId');
    }
	 
	public function product(){
        return $this->hasOne(Product::class, 'id','item_id');
    }
}
