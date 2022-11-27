<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\State;
use App\Models\Zone;


class SettingController extends Controller
{
    public function getdistrict(){
        $district=State::get();
        return response()->json(array("district" => $district), 200);
    }
    
    public function getcity($dist=false){
        if($dist)
            $city=Zone::where('stateId',$dist)->get();
        else
            $city=Zone::get();
        return response()->json(array("city" => $city), 200);
    }
}