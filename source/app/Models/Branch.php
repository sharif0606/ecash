<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model{
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
}
