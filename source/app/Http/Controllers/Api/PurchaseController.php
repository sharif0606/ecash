<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductList;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Stock;
use App\Models\Company;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\State;
use App\Models\Zone;

use App\Models\User;

use Exception;
use Carbon\Carbon;

use DB;
use PDF;
use View;

class PurchaseController extends Controller
{
    public function index($token, $limit = 10, Request $request){
        $data = User::select('id','roleId', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $page = $request->has('page') ? $request->get('page') : 1;
        
		if($data['roleId']=="9"){
			$allPurchase = Purchase::with('purchaseItem')->where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->where('userId',$data->id)->whereIn('status',[1,2])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
		elseif($data['roleId']=="8"){
			$allPurchase = Purchase::where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->whereIn('status',[1,2])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}else{
			$allPurchase = Purchase::with('purchaseItem')->where('companyId', $data->companyId)->whereIn('status',[1,2])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
        return response()->json(array("allPurchase" => $allPurchase), 200);
    }
    
    public function replaceAll($token, $limit = 10, Request $request){
        $data = User::select('id','roleId', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $page = $request->has('page') ? $request->get('page') : 1;
        
		if($data['roleId']=="9"){
		    $allPurchase = Purchase::with('purchaseItem')->where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->where('userId',$data->id)->whereIn('status',[5])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}elseif($data['roleId']=="8"){
			$allPurchase = Purchase::where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->whereIn('status',[5])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}else{
			$allPurchase = Purchase::with('purchaseItem')->where('companyId', $data->companyId)->whereIn('status',[5])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
        return response()->json(array("allPurchase" => $allPurchase), 200);
    }
    
    public function returnAll($token, $limit = 10, Request $request){
        $data = User::select('id','roleId', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $page = $request->has('page') ? $request->get('page') : 1;
        
		if($data['roleId']=="9"){
		    $allPurchase = Purchase::with('purchaseItem')->where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->where('userId',$data->id)->whereIn('status',[3,4])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}elseif($data['roleId']=="8"){
			$allPurchase = Purchase::where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->whereIn('status',[3,4])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}else{
			$allPurchase = Purchase::with('purchaseItem')->where('companyId', $data->companyId)->whereIn('status',[3,4])
			                ->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		}
        return response()->json(array("allPurchase" => $allPurchase), 200);
    }
    
	public function getProductDetails(Request $request){
		
		$b = Product::find($request->product_id);
		if($b){
			echo json_encode(array("data"=>$b,"error"=>0));
		}
		else{
			echo json_encode(array("data"=>"","error"=>"Product details not found."));
		}
    }

    public function store($token, Request $request)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $rules = ['supplier_id' => 'required', 'sub_total' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
		DB::beginTransaction();
		
		$purchase_no = Purchase::select('purchase_no')->where(company())->take(1)->orderBy('id', 'DESC')->first();
    		if($purchase_no)
    			$purchase_no = $purchase_no->purchase_no+1;
    		else
    			$purchase_no = 1;
        
		$b = new Purchase;
		$b->purchase_no 	= $purchase_no;
		$b->sup_id 			= $request->supplier_id;
		$b->type 			= "";
		$b->purchase_term 	= $request->purchase_term;
		$b->purchase_date 	= date('Y-m-d',strtotime($request->purchase_date));
		$b->sub_total 		= $request->sub_total;
		$b->total_tax 		= $request->tax;
		$b->total_dis 		= $request->discount;
		
		if($request->total > $request->total_ip)
			$b->total_due 	= ($request->total - $request->pay);
			
		$b->due_date 		= $request->due_date?date('Y-m-d',strtotime($request->due_date)):null;
		$b->total_amount	= $request->total;
		$b->note 			= $request->shop_note;
		if($request->purchase_term!=1){
			$b->cheque_no 		= $request->cheque_no;
			$b->bank_name 		= $request->bank_name;
		}else{
			$b->cheque_no 		= "";
			$b->bank_name 		= "";
		}
		
		if($request->purchase_term==4){
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
		
		$b->status 			= 1;
		$b->companyId 		= $data->companyId;
		$b->branchId 		= $data->branchId;
		$b->userId 			= $data->id;
		$b->created_at 		= Carbon::now();

		if(!!$b->save()){
			foreach($request->products as $id => $details){
				if($details['product_id'] && $details['qty']){
				    $stock=new Stock;
					$stock->companyId 	= $data->companyId;
					$stock->branchId 	= $data->branchId;
					$stock->productId=$details['product_id'];
					$stock->batchId=$details['product_id'].$b->userId.date('dmy').rand(1000,9999);
					$stock->stock=$details['qty'];
					$stock->sellPrice=$details['sellPrice'];
					$stock->buyPrice=$details['buyPrice'];
					$stock->serialNo=$details['sl_no'];
					$stock->ram=$details['ram'];
					$stock->storage=$details['storage'];
					$stock->color=$details['color'];
					$stock->imei_1=$details['imei_1'];
					$stock->imei_2=$details['imei_2'];
					
                    //print_r(DB::getQueryLog());die();
					$stock->save();

					$bi 			= new PurchaseItem;
					$bi->companyId 	= $data->companyId;
					$bi->branchId 	= $data->branchId;
					$bi->purchase_id= $b->id;
					$bi->item_id 	= $details['product_id'];
					$bi->batchId 	= $stock->batchId;
					$bi->qty 		= $details['qty'];
					$bi->price 		= $details['buyPrice'];
					$bi->discount	= $details['discount'];
					$bi->tax	 	= $details['tax'];

					$dist=0;
					if($details['discount']>0)
						$dist=($details['buyPrice']*($details['discount']/100));

					$tax=0;
					if($details['tax']>0)
						$tax=($details['buyPrice']*($details['tax']/100));

					$bi->amount	 	= (($details['buyPrice']+$dist)-$tax);
					$bi->save();
				}
			}
			DB::commit();
		    return response()->json(array("success" => 'Purchase created'), 200);
		}else{
		    DB::rollBack();
			return response()->json(array('errors' => "Please try again."), 400);
		}
    }
    
	public function changeStatus(Request $request){
		try {
			DB::beginTransaction();
			$error="";
			$s= Purchase::findOrFail(encryptor('decrypt', $request->get('bid')));
			$oldStatus=$s->status;
				if($s->status != $request->get('status')){
					$s->status=$request->get('status');
					$s->cancel_reason=$request->get('reason');
					if($s->save()){
						if($request->get('status')==2 || $request->get('status')==4){
							$items=PurchaseItem::select('batchId','item_id','qty')->where('purchase_id','=',$s->id)->get()->toArray();
							if($items){
								$mediups=array();
								foreach($items as $item){
									$medi=Stock::select('id','stock')->where('batchId',$item['batchId'])->where('productId',$item['item_id'])->first()->toArray();
									if($medi){ // check if medecine found in stock
										if($medi['stock'] >= $item['qty']){ // check if stock is available to cancel or return
											$mediups[]=array("id"=>$medi['id'],'stock'=>($medi['stock'] - $item['qty']));
										}else $error='You have already sold this product';
									}else $error='This product is not found in stock';
								}
								if($error==''){
									if($mediups){
										foreach($mediups as $mediup){
											$me=Stock::find($mediup['id']);
											$me->stock=$mediup['stock'];
											$me->save();
										}
									}else $error='This product is not found in stock';
								}
							}else $error='This product is not found in purchase item list';
						}elseif($request->get('status')==1){
							$items=PurchaseItem::where('purchase_id','=',$s->id)->get();
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
						}
						
						if($error=='Fail'){
							$rollback= Purchase::findOrFail(encryptor('decrypt', $request->get('bid')));
							$rollback->status=$oldStatus;
							$rollback->save();
						}
					}else $error='Fail';
				}else $error='Fail';
				DB::commit();
			if($error)
				echo $error;
			else
				echo "success";
		
		}catch (Exception $e) {
			dd($e);
			DB::rollBack();
			echo $error;
		}
			
    }
	
	public function show($id){
        $Purchase = Purchase::find(encryptor('decrypt', $id));
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$items = PurchaseItem::where('purchase_id','=',encryptor('decrypt', $id))->get();
		
	    if($company){
            return view('purchase.show', compact([
                'Purchase',
                'company',
    			'items'
            ]));
        }else{
             return redirect(route(currentUser().'.allPurchase'))->with($this->responseMessage(false, 'error', 'Please fillup your company information first'));
        }
    }
	

    public function purchasePDF($id,$type) {
        $Purchase = Purchase::find(encryptor('decrypt', $id));
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$items = PurchaseItem::where('purchase_id','=',encryptor('decrypt', $id))->get();
		$data = [
    		'Purchase' => $Purchase,
    		'company' => $company,
    		'items' => $items
    	];
    	$pdf = PDF::loadView('purchase.pdf', $data);
    	
    	if($type=='download')
    	    return $pdf->download('INV-'.str_pad($Purchase->purchase_no,7,'0',STR_PAD_LEFT).'.pdf');
    	else
    	    return $pdf->stream('INV-'.str_pad($Purchase->purchase_no,7,'0',STR_PAD_LEFT).'.pdf');
    }

    public function editForm($id){
        $purchase = Purchase::find(encryptor('decrypt', $id));
		$items = PurchaseItem::where('purchase_id','=',encryptor('decrypt', $id))->get();
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$allProduct = Product::where(company())->where(branch())->where('status', 1)->orderBy('name', 'DESC')->get();
		$allSupplier = Supplier::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
		$allCat = Category::where('status', 1)->orderBy('name', 'DESC')->get();
		$allBrand = Brand::where('status', 1)->orderBy('name', 'DESC')->get();

		return view('purchase.edit', compact([
			'purchase','company','allProduct','allSupplier','allCat','allBrand','items'
		]));
    }

    public function update(UpdatePurchaseRequest $request){
        try {
			DB::beginTransaction();
            $b = Purchase::find(encryptor('decrypt', $request->id));
			$b->sup_id 			= $request->sup_id;
			$b->type 			= $request->type;
			$b->purchase_term 	= $request->purchase_term;
			$b->purchase_date 	= date('Y-m-d',strtotime($request->purchase_date));
			$b->sub_total 		= $request->sub_total_i;
			$b->total_tax 		= $request->tax_i;
			$b->total_dis 		= $request->discount_i;
			
			if($request->total_i > $request->total_ip)
				$b->total_due 	= ($request->total_i - $request->total_ip);

			$b->due_date 		= $request->due_date?date('Y-m-d',strtotime($request->due_date)):null;
			$b->total_amount	= $request->total_i;
			$b->note 			= $request->note;
			if($request->purchase_term!=1){
				$b->cheque_no 		= $request->cheque_no;
				$b->bank_name 		= $request->bank_name;
			}else{
				$b->cheque_no 		= "";
				$b->bank_name 		= "";
			}
			
			if($request->purchase_term==4){
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
			
			$b->status 			= 1;
			$b->userId 			= encryptor('decrypt', request()->session()->get('user'));
			$b->updated_at 		= Carbon::now();

            if(!!$b->save()){
				$olditem=PurchaseItem::where('purchase_id',$b->id)->get();
				
				if(!empty($olditem)){
					foreach($olditem as $oi){
						$stock=Stock::where('batchId',$oi->batchId)->first();
						$stock->stock=($stock->stock - $oi->qty);
						if(!!$stock->save()){
							PurchaseItem::where('id',$oi->id)->delete();
						}
					}
				}

				foreach($request->products as $id => $details){
					if($details['product_id'] && $details['qty']){
						$oldstock=false;

						if($id==(count($request->products)-1)){
							if($request->batchId){
								$oldstock=true;
                    			$stock=Stock::find(explode('-',$request->batchId)[3]);
							}elseif($details['batchId']){
								$oldstock=true;
								$stock=Stock::find(explode('-',$details['batchId'])[3]);
							}
						}else{
							if($details['batchId']){
								$oldstock=true;
								$stock=Stock::find(explode('-',$details['batchId'])[3]);
							}
						}
						if($oldstock==true){
							$stock->stock=($stock->stock + $details['qty']);
							$stock->sellPrice=$details['sellPrice'];
							$stock->buyPrice=$details['buyPrice'];
						}else{
							$stock=new Stock;
							$stock->companyId 	= $data->companyId;
							$stock->branchId 	= $data->branchId;
							$stock->productId=$details['product_id'];
							$stock->batchId=$details['product_id'].$b->userId.time();
							$stock->stock=$details['qty'];
							$stock->sellPrice=$details['sellPrice'];
							$stock->buyPrice=$details['buyPrice'];
						}
                        //print_r(DB::getQueryLog());die();
    					$stock->save();

						$bi 			= new PurchaseItem;
						$bi->companyId 	= $data->companyId;
						$bi->branchId 	= $data->branchId;
						$bi->purchase_id= $b->id;
						$bi->item_id 	= $details['product_id'];
						$bi->batchId 	= $stock->batchId;
						$bi->qty 		= $details['qty'];
						$bi->price 		= $details['buyPrice'];
						$bi->discount	= $details['discount'];
						$bi->tax	 	= $details['tax'];

						$dist=0;
						if($details['discount']>0)
							$dist=($details['buyPrice']*($details['discount']/100));

						$tax=0;
						if($details['tax']>0)
							$tax=($details['buyPrice']*($details['tax']/100));

						$bi->amount	 	= (($details['buyPrice']+$dist)-$tax);
						$bi->save();
					}
				}
				DB::commit();	
				if($request->print_op){
				    return redirect(route(currentUser().'.purchaseShow',[encryptor('encrypt', $b->id)]))->with($this->responseMessage(true, null, 'Purchase created'));
				}else{
				    return redirect(route(currentUser().'.allPurchase'))->with($this->responseMessage(true, null, 'Purchase created'));
				}
			}
			else{
				return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
			}
		}catch (Exception $e) {
			DB::rollBack();
			dd($e);
			return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
		}

    }

    public function replaceForm($id){
		$purchase = Purchase::find(encryptor('decrypt', $id));
        $oldid= encryptor('decrypt', $id);
		$company = Company::where(company())->orderBy('id', 'DESC')->first();
		$allProduct = Product::where(company())->where(branch())->where('status', 1)->orderBy('name', 'DESC')->get();
		$allSupplier = Supplier::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
		$allCat = Category::where('status', 1)->orderBy('name', 'DESC')->get();
		$allBrand = Brand::where('status', 1)->orderBy('name', 'DESC')->get();

		return view('purchase.replace', compact([
			'purchase','oldid','company','allProduct','allSupplier','allCat','allBrand'
		]));
    }

	public function replace(NewPurchaseRequest $request)
    {
        try {
			DB::beginTransaction();
			/* old purchase porduct replace to stock */
			$olditem=PurchaseItem::where('purchase_id',$request->bill_ref)->get();
			if(!empty($olditem)){
				foreach($olditem as $oi){
					$ostock=Stock::where('batchId',$oi->batchId)->first();
					$ostock->stock=($ostock->stock - $oi->qty);
					$ostock->save();
				}
			}
			/* old purchase porduct replace to stock */


			$purchase_no = Purchase::select('purchase_no')->where(company())->take(1)->orderBy('id', 'DESC')->first();
			if($purchase_no)
				$purchase_no = $purchase_no->purchase_no+1;
			else
				$purchase_no = 1;
            
			$b = new Purchase;
			$b->purchase_no 	= $purchase_no;
			$b->sup_id 			= $request->sup_id;
			$b->type 			= $request->type;
			$b->purchase_term 	= $request->purchase_term;
			$b->purchase_date 	= date('Y-m-d',strtotime($request->purchase_date));
			$b->sub_total 		= $request->sub_total_i;
			$b->total_tax 		= $request->tax_i;
			$b->total_dis 		= $request->discount_i;

			if($request->total_i > ($request->total_ip+$request->old_pay))
				$b->total_due 	= ($request->total_i - ($request->total_ip+$request->old_pay));
				
			$b->due_date 		= $request->due_date?date('Y-m-d',strtotime($request->due_date)):null;
			$b->total_amount	= $request->total_i;
			$b->note 			= $request->note;
			if($request->purchase_term!=1){
				$b->cheque_no 	= $request->cheque_no;
				$b->bank_name 	= $request->bank_name;
			}else{
				$b->cheque_no 	= "";
				$b->bank_name 	= "";
			}
			
			if($request->purchase_term==4){
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
			
			$b->status 			= 1;
			$b->to_ref_id 		= $request->bill_ref;
			$b->companyId 		= $data->companyId;
			$b->branchId 		= $data->branchId;
			$b->userId 			= encryptor('decrypt', request()->session()->get('user'));
			$b->created_at 		= Carbon::now();

			if(!!$b->save()){
				foreach($request->products as $id => $details){
					if($details['product_id'] && $details['qty']){
						$oldbatch=false;
						if($id==(count($request->products)-1)){
							if($request->batchId){
								$oldbatch=true;
								$stock=Stock::find(explode('-',$request->batchId)[3]);
							}else{
								$stock=new Stock;
							}
						}else{
							if($details['batchId']){
								$oldbatch=true;
								$stock=Stock::find(explode('-',$details['batchId'])[3]);
							}else{
								$stock=new Stock;
							}
						}

						if($oldbatch==true){
							$stock->stock=($stock->stock + $details['qty']);
							$stock->sellPrice=$details['sellPrice'];
							$stock->buyPrice=$details['buyPrice'];
						}else{
							$stock->companyId 	= $data->companyId;
							$stock->branchId 	= $data->branchId;
							$stock->productId=$details['product_id'];
							$stock->batchId=$details['product_id'].$b->userId.time();
							$stock->stock=$details['qty'];
							$stock->sellPrice=$details['sellPrice'];
							$stock->buyPrice=$details['buyPrice'];
						}
                        //print_r(DB::getQueryLog());die();
    					$stock->save();

						$bi 			= new PurchaseItem;
						$bi->companyId 	= $data->companyId;
						$bi->branchId 	= $data->branchId;
						$bi->purchase_id= $b->id;
						$bi->item_id 	= $details['product_id'];
						$bi->batchId 	= $stock->batchId;
						$bi->qty 		= $details['qty'];
						$bi->price 		= $details['buyPrice'];
						$bi->discount	= $details['discount'];
						$bi->tax	 	= $details['tax'];

						$dist=0;
						if($details['discount']>0)
							$dist=($details['buyPrice']*($details['discount']/100));

						$tax=0;
						if($details['tax']>0)
							$tax=($details['buyPrice']*($details['tax']/100));

						$bi->amount	 	= (($details['buyPrice']+$dist)-$tax);
						$bi->save();
					}
				}
				$oldpurchase = Purchase::find($request->bill_ref);
				$oldpurchase->status = 5;
				$oldpurchase->bill_reff = $b->id;
				$oldpurchase->cancel_reason = $request->cancel_reason;
				$oldpurchase->save();
				DB::commit();
				if($request->print_op){
				    return redirect(route(currentUser().'.purchaseShow',[encryptor('encrypt', $b->id)]))->with($this->responseMessage(true, null, 'Purchase created'));
				}else{
				    return redirect(route(currentUser().'.allPurchase'))->with($this->responseMessage(true, null, 'Purchase created'));
				}
			}
			else{
				return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
			}
		}catch (Exception $e) {
			//dd($e);
			DB::rollBack();
			return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
		}
    }
}
