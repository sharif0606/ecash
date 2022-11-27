<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['custCode', 'name', 'type'];
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function sell(){
        return $this->hasMany(Bill::class,'customer_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'contact_person','id');
    }
    protected $casts = [
        'ver_code_send_at' => 'datetime'
    ];
}
