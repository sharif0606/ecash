<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
	public $timestamps = false;
    
	public function product(){
        return $this->hasOne(Product::class, 'id','item_id');
    }
	public function batch(){
        return $this->hasOne(Stock::class, 'batchId','batchId');
    }
}
