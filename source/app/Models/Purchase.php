<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function purchaseItem(){
        return $this->hasMany(PurchaseItem::class,'purchase_id','id');
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'sup_id','id');
    }
}
