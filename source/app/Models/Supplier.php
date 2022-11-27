<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model{
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
    public function purchase(){
        return $this->hasMany(Purchase::class,'sup_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'contact_person','id');
    }
}
