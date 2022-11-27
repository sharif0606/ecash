<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;

use App\Models\UserPackage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Company;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\State;
use App\Models\Zone;

use App\Models\User;

use Exception;
use Carbon\Carbon;

use DB;
use PDF;
use View;
use Response;

class BillController extends Controller{


    public function index($token, $limit = 10, Request $request){
        $data = User::select('id','roleId','branchId','companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $companyId = $data['companyId'];
        $branchId = $data['branchId'];
        $page = $request->has('page') ? $request->get('page') : 1;
        
		if($data['roleId']=="9"){
        	$u=$data['id'];
			$allBill = Bill::with('billitem','customer')->where('companyId',$companyId)->where('branchId',$branchId)
			            ->where('userId',$u)->whereIn('status',[1,2])
			            ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
		elseif($data['roleId']=="8"){
			$allBill = Bill::with('billitem','customer')->where('companyId',$companyId)->where('branchId',$branchId)
			            ->whereIn('status',[1,2])->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
		else{
			$allBill = Bill::with('billitem','customer')->where('companyId',$companyId)
			            ->whereIn('status',[1,2])
			            ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
        return response()->json(array("allBill" => $allBill), 200);
    }

    public function replaceAll($token, $limit = 10, Request $request){
		$data = User::select('id','roleId','branchId','companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $companyId = $data['companyId'];
        $branchId = $data['branchId'];
        $page = $request->has('page') ? $request->get('page') : 1;
        
		if($data['roleId']=="9"){
        	$u=$data['id'];
			$allBill = Bill::with('billitem')->where('companyId',$companyId)->where('branchId',$branchId)
			            ->where('userId',$u)->whereIn('status',[5])
			            ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
		elseif($data['roleId']=="8"){
			$allBill = Bill::with('billitem')->where('companyId',$companyId)->where('branchId',$branchId)
			            ->whereIn('status',[5])->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
		else{
			$allBill = Bill::with('billitem')->where('companyId',$companyId)
			            ->whereIn('status',[5])
			            ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
        return response()->json(array("allBill" => $allBill), 200);
    }

    public function returnAll($token, $limit = 10, Request $request){
		$data = User::select('id','roleId','branchId','companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $companyId = $data['companyId'];
        $branchId = $data['branchId'];
        $page = $request->has('page') ? $request->get('page') : 1;
        
		if($data['roleId']=="9"){
        	$u=$data['id'];
			$allBill = Bill::with('billitem')->where('companyId',$companyId)->where('branchId',$branchId)
			            ->where('userId',$u)->whereIn('status',[3,4])
			            ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
		elseif($data['roleId']=="8"){
			$allBill = Bill::with('billitem')->where('companyId',$companyId)->where('branchId',$branchId)
			            ->whereIn('status',[3,4])->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
		else{
			$allBill = Bill::with('billitem')->where('companyId',$companyId)
			            ->whereIn('status',[3,4])
			            ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
        return response()->json(array("allBill" => $allBill), 200);
    }

	public function getBatch($token,$id){
		$data = User::select('id','roleId','branchId','companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $companyId = $data['companyId'];
        $branchId = $data['branchId'];
        
        $batchstock=Stock::where('companyId',$companyId)->where('branchId',$branchId)->where('productId',$id)->where('stock','>',0)->get();
		if($batchstock)
		    return response()->json(array("batchstock" => $batchstock), 200);
		else 
		    return response()->json(array('errors' => [0 => 'No active batch found']), 400);
    }
	
    public function store($token, Request $request){
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }

        $companyId = $data['companyId'];
        $branchId = $data['branchId'];

        $active_pack=UserPackage::where('companyId',$companyId)->where('branchId',$branchId)->where('status',1)->sum('noofbill');
        $bill=Bill::where('companyId',$companyId)->where('branchId',$branchId)->whereIn('status',[1])->count();
        if($active_pack < $bill){
            return response()->json(array('errors' => [0 => 'you don\'t have enough invoice']), 400);
            exit;
        }

        $rules = ['customer_id' => 'required', 'sub_total' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
			DB::beginTransaction();
			$bill_no = Bill::select('bill_no')
			                ->where('companyId',$companyId)->where('branchId',$branchId)
			                ->take(1)->orderBy('id', 'DESC')->first();
			
			if($bill_no)
				$bill_no = $bill_no->bill_no+1;
			else
				$bill_no = 1;
		    
			$b = new Bill;
			$b->bill_no 		= $bill_no;
			$b->bill_date 		= date('Y-m-d',strtotime($request->bill_date));
			$b->due_date 		= $request->due_date?date('Y-m-d',strtotime($request->due_date)):null;
			$b->customer_id 	= $request->customer_id;
			$b->type 			= "";
			$b->bill_term 		= $request->bill_term;
			$b->sub_total 		= $request->sub_total;
			$b->total_tax 		= $request->total_tax;
			$b->total_dis 		= $request->total_dis;
			$b->total_amount 	= $request->total_amount;
			$b->actual_amount 	= $request->actual_amount;
			
			if($request->actual_amount > $request->total_pay)
				$b->total_due 	= ($request->actual_amount - $request->total_pay);

			$b->sales_person 	= $request->sales_person;
			$b->note 			= $request->shop_note;
			if($request->bill_term!=1){
				$b->cheque_no 		= $request->cheque_no;
				$b->bank_name 		= $request->bank_name;
			}else{
				$b->cheque_no 		= "";
				$b->bank_name 		= "";
			}
			
			if($request->bill_term==4){
				$b->mbank_name 		= $request->mbank_name;
				$b->sender_no 		= $request->sender_no;
				$b->receiver_no 	= $request->receiver_no;
				$b->m_note 	        = $request->m_note;
			}else{
				$b->mbank_name 		= "";
				$b->sender_no 		= "";
				$b->sender_no 		= "";
				$b->m_note 		    = "";
			}
			
			$b->status = 1;
			$b->companyId = $companyId;
			$b->branchId = $branchId;
			$b->userId = $data['id'];
			$b->created_at = Carbon::now();

			if(!!$b->save()){
			    foreach($request->products as $id => $details){
				    if($details['product_id'] && $details['qty']){
        				$bi 			    = new BillItem;
        				$bi->companyId 	    = $companyId;
        				$bi->branchId 	    = $branchId;
        				$bi->bill_id 	    = $b->id;
        				$bi->item_id 	    = $details['product_id'];
        				$bi->batchId 	    = $details['batchId'];
        				$bi->cost_price     = $details['sellPrice'];
        				$bi->qty 		    = $details['qty'];
        				$bi->price 		    = $details['sub_price'];
        				$bi->discount	    = $details['discount'];
        				$bi->tax	 	    = $details['tax'];
        				$bi->amount	 	    = $details['price'];
        				$bi->actual_payment	= $details['actual_payment']>0?$details['actual_payment']:$details['price'];
        				$bi->save();
    					
                        $medi=Stock::where('batchId',$details['batchId'])->first();
                        $medi->stock=($medi->stock - $details['qty']);
                        $medi->save();
					}
				}
				DB::commit();
				
				return response()->json(array("success" => 'Bill created'), 200);
			}else{
			    return response()->json(array('errors' => [0 => 'Please try again!']), 400);
			}
    }
	
	public function changeStatus(Request $request){
		$error="";
		$s= Bill::findOrFail(encryptor('decrypt', $request->get('bid')));
			if($s->status != $request->get('status')){
				$s->status=$request->get('status');
				$s->cancel_reason=$request->get('reason');
				if($s->save()){
					if($request->get('status')==2 || $request->get('status')==4){
						$items=BillItem::where('bill_id','=',$s->id)->get();
						if($items){
							foreach($items as $item){
								$medi=Stock::select('id')->where('batchId',$item->batchId)->where('productId',$item->item_id)->first();
								if($medi){
									$mediup=Stock::find($medi->id);
									$mediup->stock=($mediup->stock + $item->qty);
									$mediup->save();
								}
								else $error='Fail';
							}
						}else $error='Fail';
					}elseif($request->get('status')==1){
						$items=BillItem::where('bill_id','=',$s->id)->get();
						if($items){
							foreach($items as $item){
								$medi=Stock::select('id')->where('batchId',$item->batchId)->where('productId',$item->item_id)->first();
								if($medi){
									$mediup=Stock::find($medi->id);
									$mediup->stock=($mediup->stock - $item->qty);
									$mediup->save();
								}
								else $error='Fail';
							}
						}else $error='Fail';
					}
				}else $error='Fail';
			}else $error='Fail';
			
		if($error)
			echo $error;
		else
			echo "success";
    }
	
	
	public function billShow($id){
        $bill = Bill::find(encryptor('decrypt', $id));
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$items = BillItem::where('bill_id','=',encryptor('decrypt', $id))->get();
		
		if($company){
            return view('bill.show', compact([
                'bill',
                'company',
    			'items'
            ]));
        }else{
            return redirect(route(currentUser().'.allBill'))->with($this->responseMessage(false, 'error', 'Please fillup your company information first'));
        }
        
    }

    public function billPDF($id,$type) {
        $bill = Bill::find(encryptor('decrypt', $id));
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$items = BillItem::where('bill_id','=',encryptor('decrypt', $id))->get();
		if($company->invoice)
			$inv=$company->invoice;
		else
			$inv="invoice_first";
			
		$data = [
    		'bill' => $bill,
    		'company' => $company,
    		'items' => $items
    	];
    	$pdf = PDF::loadView('bill.'.$inv, $data);
    	
    	if($type=='download')
    	    return $pdf->download('INV-'.str_pad($bill->bill_no,7,'0',STR_PAD_LEFT).'.pdf');
    	else
    	    return $pdf->stream('INV-'.str_pad($bill->bill_no,7,'0',STR_PAD_LEFT).'.pdf');
    }
    
    public function editForm($id){
        $bill = Bill::find(encryptor('decrypt', $id));
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$items = BillItem::where('bill_id','=',encryptor('decrypt', $id))->get();
		
		$allProduct = Product::where(company())->where(branch())->where('status', 1)->whereHas('stocks', function ($query) {
						$query->where('stock','>','0');
					})->orderBy('name', 'DESC')->get();
		$allCustomer = Customer::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
		$allCat = Category::where('status', 1)->orderBy('name', 'DESC')->get();
		$allBrand = Brand::where('status', 1)->orderBy('name', 'DESC')->get();

		return view('bill.edit', compact([
			'bill','company','allProduct','allCustomer','allCat','allBrand','items'
		]));
    }

    public function update(UpdateBillRequest $request){
        try {
			DB::beginTransaction();
            $b = Bill::find(encryptor('decrypt', $request->id));
			$b->bill_date 		= date('Y-m-d',strtotime($request->bill_date));
			$b->due_date 		= $request->due_date?date('Y-m-d',strtotime($request->due_date)):null;
			$b->customer_id 	= $request->customer_id;
			$b->type 			= $request->type;
			$b->bill_term 		= $request->bill_term;
			$b->sub_total 		= $request->sub_total_i;
			$b->total_tax 		= $request->tax_i;
			$b->total_dis 		= $request->discount_i;
			$b->total_amount 	= $request->total_i;
			$b->actual_amount 	= $request->actotal_i;
			
			if($request->actotal_i > $request->total_ip)
				$b->total_due 	= ($request->actotal_i - $request->total_ip);

			$b->sales_person 	= $request->sales_person;
			$b->note 			= $request->note;
			if($request->bill_term!=1){
				$b->cheque_no 		= $request->cheque_no;
				$b->bank_name 		= $request->bank_name;
			}else{
				$b->cheque_no 		= "";
				$b->bank_name 		= "";
			}
			
			if($request->bill_term==4){
				$b->mbank_name 		= $request->mbank_name;
				$b->sender_no 		= $request->sender_no;
				$b->receiver_no 	= $request->receiver_no;
				$b->m_note 	        = $request->m_note;
			}else{
				$b->mbank_name 		= "";
				$b->sender_no 		= "";
				$b->sender_no 		= "";
				$b->m_note 		    = "";
			}
			$b->status = 1;
			$b->companyId = company()['companyId'];
			$b->branchId = branch()['branchId'];
			$b->userId = encryptor('decrypt', request()->session()->get('user'));
			$b->updated_at = Carbon::now();

            if(!!$b->save()){
				$olditem=BillItem::where('bill_id',$b->id)->get();
				
				if(!empty($olditem)){
					foreach($olditem as $oi){
						$stock=Stock::where('batchId',$oi->batchId)->first();
						$stock->stock=($stock->stock + $oi->qty);
						if(!!$stock->save()){
							BillItem::where('id',$oi->id)->delete();
						}
					}
				}

				foreach($request->products as $id => $details){
					if($details['product_id'] && $details['qty']){
    					$bi 			= new BillItem;
    					$bi->companyId 	= company()['companyId'];
    					$bi->branchId 	= branch()['branchId'];
    					$bi->bill_id 	= $b->id;
    					$bi->item_id 	= $details['product_id'];

						if($id==(count($request->products)-1)){
							if($request->batchId){
    							$bi->batchId 	= explode('-',$request->batchId)[0];
    							$bi->cost_price = explode('-',$request->batchId)[5];
							}else{
								$bi->batchId 	= explode('-',$details['batchId'])[0];
								$bi->cost_price = explode('-',$details['batchId'])[5];
							}
						}else{
    						$bi->batchId 	= explode('-',$details['batchId'])[0];
    						$bi->cost_price = explode('-',$details['batchId'])[5];
						}

    					$bi->qty 		= $details['qty'];
    					$bi->price 		= $details['sub_price'];
    					$bi->discount	= $details['discount'];
    					$bi->tax	 	= $details['tax'];
    					$bi->amount	 	= $details['price'];
    					$bi->actual_payment	 	= $details['actual_payment']>0?$details['actual_payment']:$details['price'];
    					$bi->save();
    					//DB::enableQueryLog();
						if($id==(count($request->products)-1))
							if($request->batchId)
								$medi=Stock::where('batchId',explode('-',$request->batchId)[0])->first();
							else
								$medi=Stock::where('batchId',explode('-',$details['batchId'])[0])->first();
						else
                    		$medi=Stock::where('batchId',explode('-',$details['batchId'])[0])->first();
                        //print_r(DB::getQueryLog());die();
    				
    					$medi->stock=($medi->stock - $details['qty']);
    					$medi->save();
					}
				}
				DB::commit();
				
				if($request->print_op)
				    return redirect(route(currentUser().'.billShow',[encryptor('encrypt', $b->id)]))->with($this->responseMessage(true, null, 'Bill created'));
				else
				    return redirect(route(currentUser().'.allBill'))->with($this->responseMessage(true, null, 'Bill created'));
			}else{
				return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
			}
        } catch (Exception $e) {
			DB::rollBack();
            //dd($e);
            return redirect(route(currentUser().'.allBill'))->with($this->responseMessage(false, 'error', 'Please try again!'));
        }

    }

    public function delete($id){
        /*try {
            $bill = Bill::find(encryptor('decrypt', $id));
            if($bill != null){
                BillItem::where(['bill_id' => encryptor('decrypt', $id)])->delete();
                $bill->delete();
                return redirect()->back()->with($this->responseMessage(true, 'null', 'Bill deleted'));
            }

        }catch (Exception $e) {
            //dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }*/
    }

    public function replaceForm($id){
		$bill = Bill::find(encryptor('decrypt', $id));
        $oldid= encryptor('decrypt', $id);
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$allProduct = Product::where(company())->where(branch())->where('status', 1)->whereHas('stocks', function ($query) {
							$query->where('stock','>','0');
						})->orderBy('name', 'DESC')->get();
		$allCustomer = Customer::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
		$allCat = Category::where('status', 1)->orderBy('name', 'DESC')->get();
		$allBrand = Brand::where('status', 1)->orderBy('name', 'DESC')->get();

		return view('bill.replace', compact([
			'bill','oldid','company','allProduct','allCustomer','allCat','allBrand'
		]));
    }

	public function replace(NewBillRequest $request)
    {
        try {
			DB::beginTransaction();
			/* old bill porduct replace to stock */
			$olditem=BillItem::where('bill_id',$request->bill_ref)->get();
				
			if(!empty($olditem)){
				foreach($olditem as $oi){
					$stock=Stock::where('batchId',$oi->batchId)->first();
					$stock->stock=($stock->stock + $oi->qty);
					$stock->save();
				}
			}
			
			/* old bill porduct replace to stock */
			$bill_no = Bill::select('bill_no')->where(company())->where(branch())->take(1)->orderBy('id', 'DESC')->first();
			
			if($bill_no)
				$bill_no = $bill_no->bill_no+1;
			else
				$bill_no = 1;
		    
			$b = new Bill;
			$b->bill_no 		= $bill_no;
			$b->bill_date 		= date('Y-m-d',strtotime($request->bill_date));
			$b->due_date 		= $request->due_date?date('Y-m-d',strtotime($request->due_date)):null;
			$b->customer_id 	= $request->customer_id;
			$b->type 			= $request->type;
			$b->bill_term 		= $request->bill_term;
			$b->sub_total 		= $request->sub_total_i;
			$b->total_tax 		= $request->tax_i;
			$b->total_dis 		= $request->discount_i;
			$b->total_amount 	= $request->total_i;
			$b->actual_amount 	= $request->actotal_i;
			
			if($request->actotal_i > ($request->total_ip+$request->old_pay))
				$b->total_due 	= ($request->actotal_i - ($request->total_ip+$request->old_pay));
            
            $b->sales_person 	= $request->sales_person;
			$b->note 			= $request->note;
			if($request->bill_term!=1){
				$b->cheque_no 	= $request->cheque_no;
				$b->bank_name 	= $request->bank_name;
			}else{
				$b->cheque_no 	= "";
				$b->bank_name 	= "";
			}
			
			if($request->bill_term==4){
				$b->mbank_name 		= $request->mbank_name;
				$b->sender_no 		= $request->sender_no;
				$b->receiver_no 	= $request->receiver_no;
				$b->m_note 	        = $request->m_note;
			}else{
				$b->mbank_name 		= "";
				$b->sender_no 		= "";
				$b->sender_no 		= "";
				$b->m_note 		    = "";
			}
			$b->status = 1;
			$b->to_ref_id 		= $request->bill_ref;
			$b->companyId 		= company()['companyId'];
			$b->branchId 		= branch()['branchId'];
			$b->userId 			= encryptor('decrypt', request()->session()->get('user'));
			$b->created_at 		= Carbon::now();

			if(!!$b->save()){
				foreach($request->products as $id => $details){
					if($details['product_id'] && $details['qty']){
    					$bi 			= new BillItem;
    					$bi->companyId 	= company()['companyId'];
    					$bi->branchId 	= branch()['branchId'];
    					$bi->bill_id 	= $b->id;
    					$bi->item_id 	= $details['product_id'];
						if($id==(count($request->products)-1))
    						$bi->batchId 	= explode('-',$request->batchId)[0];
						else
    						$bi->batchId 	= explode('-',$details['batchId'])[0];
    					$bi->qty 		= $details['qty'];
    					$bi->price 		= $details['sub_price'];
    					$bi->discount	= $details['discount'];
    					$bi->tax	 	= $details['tax'];
    					$bi->amount	 	= $details['price'];
    					$bi->actual_payment	 	= $details['actual_payment']>0?$details['actual_payment']:$details['price'];
    					$bi->save();
    					//DB::enableQueryLog();
						if($id==(count($request->products)-1))
                    		$medi=Stock::find(explode('-',$request->batchId)[4]);
						else
                    		$medi=Stock::find(explode('-',$details['batchId'])[4]);
                        //print_r(DB::getQueryLog());die();
    				
    					$medi->stock=($medi->stock - $details['qty']);
    					$medi->save();
					}
				}
				$oldbill = Bill::find($request->bill_ref);
				$oldbill->status = 5;
				$oldbill->bill_reff = $b->id;
				$oldbill->cancel_reason = $request->cancel_reason;
				$oldbill->save();
				DB::commit();
				
				if($request->print_op)
				    return redirect(route(currentUser().'.billShow',[encryptor('encrypt', $b->id)]))->with($this->responseMessage(true, null, 'Bill created'));
				else
				    return redirect(route(currentUser().'.allBill'))->with($this->responseMessage(true, null, 'Bill created'));
			}
			else{
				return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
			}
		}catch (Exception $e) {
			DB::rollBack();
			//dd($e);
			return redirect()->back()->with($this->responseMessage(false, 'error', $e));
		}
    }


    public function allInvoice($id){
		$allBill = Bill::where('customer_id','=',encryptor('decrypt', $id))
			->orderBy('id', 'DESC')
			->paginate(25);
		$billSummary = Bill::select('customers.id as customerId','customers.name','customers.custCode',DB::raw("COUNT(bills.id) as totalPurchase"), DB::raw("SUM(bills.total_due) as totalDue"), DB::raw("SUM(bills.total_amount) as totalAmount"))
		->where('bills.customer_id','=',encryptor('decrypt', $id))
		->join('customers','bills.customer_id','=','customers.id')
		->orderBy('bills.id', 'DESC')
		->first();
		// print_r($billSummary->toArray());die();
		return view('bill.invoiceList', compact('allBill', 'billSummary'));
	}

	public function searchDueCustomerForm(){
		return view('bill.searchDueCustomerForm');
	}

	public function searchDueCustomer(Request $request){
		$dueBillSummary = Bill::select('customers.id','customers.name','customers.custCode',DB::raw("COUNT(bills.id) as totalPurchase"), DB::raw("SUM(bills.total_due) as totalDue"), DB::raw("SUM(bills.total_amount) as totalAmount"))
		->where('customers.custCode','like','%'.$request->customerNumber.'%')
		->join('customers','bills.customer_id','=','customers.id')
		->orderBy('bills.id', 'DESC')
		->first();
		$output = '';
		if($dueBillSummary->id != null || $dueBillSummary->id != ''){
			$output .= '<ul class="d-flex align-center justify-content-between customer-summary mb-3 p-3">
				<li>
					<strong>Customer </strong><span class="d-block">'.$dueBillSummary->name.'</span>
				</li>
				<li>
					<strong>Contact </strong><span class="d-block">'.$dueBillSummary->custCode.'</span>
				</li>
				<li>
					<strong>Total Purchase </strong><span class="d-block">'.$dueBillSummary->totalPurchase.'</span>
				</li>
				<li>
					<strong>Total Amount </strong><span class="d-block">'.$dueBillSummary->totalAmount.'</span>
				</li>

				<li>';
				if($dueBillSummary->totalDue <= 0.00){
					$output .= '<strong>Total Due </strong><span class="text-success d-block">Clear</span>';
				}else{
					$output .= '<strong>Total Due </strong><span class="text-danger d-block">'.$dueBillSummary->totalDue.'</span></li>';
				}

				if($dueBillSummary->totalDue > 0.00){
					$output .= '<li><button id="payDueForm" data-customerId="'.$dueBillSummary->id.'" data-totalDue="'.$dueBillSummary->totalDue.'" class="btn btn-info">Due Pay</button>
					</li>';
				}
					
				$output .= '</ul>';
		}else{
			
			$output .= '<ul class="d-flex align-center justify-content-between customer-summary mb-3 p-3">
					<li class="text-center w-100">
						Customer not found
					</li>
				</ul>';
		}
		return Response::json(['data' => $output]);
	}

	public function payDue(Request $request){
		$payAbleAmount = (float) $request->payAbleAmount;
		$allBill = Bill::where('customer_id','=',$request->customerId)
		->where('total_due','>',0.00)
		->orderBy('id', 'DESC')
		->get();
		if(count($allBill) > 0){
			foreach($allBill as $bill){
				if((float) $bill->total_due >= $payAbleAmount){
					$dueBillAfterPay = (float) $bill->total_due - $payAbleAmount;
					$bill->total_due = $dueBillAfterPay;
					$bill->save();
					break;
				}else{
					$dueBillAfterPay2 = round($payAbleAmount - (float) $bill->total_due, 2);
					$bill->total_due = (float) $bill->total_due - ($payAbleAmount - $dueBillAfterPay2);
					$payAbleAmount = $dueBillAfterPay2;
				}

				$bill->save();
			}
			return Response::json(['message' => 'Pay success', 'status' => true]);
		}else{
			return Response::json(['message' => 'Customer not found', 'status' => false]);
		}
		
	}
}