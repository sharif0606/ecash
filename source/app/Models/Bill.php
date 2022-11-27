<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = ['bill_no', 'customer_id', 'bill_date'];
    public function billitem(){
        return $this->hasMany(BillItem::class);
    }
	
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
	
    public function user(){
        return $this->belongsTo(User::class,'userId');
    }
}
