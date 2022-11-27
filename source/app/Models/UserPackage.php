<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    public function package(){
        return $this->belongsTo(Package::class,'packageId','id');
    }
	
    public function company(){
        return $this->belongsTo(Company::class,'companyId','companyId');
    }
    
    public function branch(){
        return $this->belongsTo(Company::class,'branchId','branchId');
    }
	
    public function user(){
        return $this->belongsTo(User::class,'userId');
    }
    
    public function requested(){
        return $this->belongsTo(User::class,'requestedBy');
    }
}
