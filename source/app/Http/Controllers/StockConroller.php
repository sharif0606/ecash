<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Stock\NewStockRequest;
use App\Http\Requests\Stock\UpdateStockRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\MedicineStock;

class StockConroller extends Controller
{
    use ResponseTrait;
    
    public function index(){
		$allStock = MedicineStock::where(company())->orderBy('id', 'DESC')->paginate(25);
        return view('stock.index', compact('allStock'));
    }

    
	public function changeStatus(Request $request){
		$error="";
		$s= MedicineStock::findOrFail(encryptor('decrypt', $request->get('bid')));
		$s->status=$request->get('status');
		if(!$s->save()) $error='Fail';
			
		if($error)
			echo $error;
		else
			echo "success";
    }
    
}
