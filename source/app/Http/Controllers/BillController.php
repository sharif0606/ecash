<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\Bill\NewBillRequest;
use App\Http\Requests\Bill\UpdateBillRequest;
use App\Http\Traits\ResponseTrait;

use App\Models\Customer;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;

use App\Models\UserPackage;

use App\Models\Company;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\State;
use App\Models\Zone;

use Exception;
use Carbon\Carbon;

use DB;
use PDF;
use View;
use Response;

class BillController extends Controller
{
    use ResponseTrait;
    
    public function __construct() {
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        View::share('allState', $allState);
        View::share('allZone', $allZone);
    }

    public function index(){
		if(currentUser()=="salesman"){
        	$u=encryptor('decrypt', request()->session()->get('user'));
			$allBill = Bill::where(company())->where(branch())->where('userId',$u)->whereIn('status',[1,2])->orderBy('id', 'DESC')->paginate(25);
		}
		elseif(currentUser()=="salesmanager"){
			$allBill = Bill::where(company())->where(branch())->whereIn('status',[1,2])->orderBy('id', 'DESC')->paginate(25);
		}
		else{
			$allBill = Bill::where(company())->whereIn('status',[1,2])->orderBy('id', 'DESC')->paginate(25);
		}
        return view('bill.index', compact('allBill'));
    }

    public function replaceAll(){
		if(currentUser()=="salesman"){
        	$u=encryptor('decrypt', request()->session()->get('user'));
			$allBill = Bill::where(company())->where(branch())->where('userId',$u)->whereIn('status',[5])->orderBy('id', 'DESC')->paginate(25);
		}
		elseif(currentUser()=="salesmanager"){
			$allBill = Bill::where(company())->where(branch())->whereIn('status',[5])->orderBy('id', 'DESC')->paginate(25);
		}
		else{
			$allBill = Bill::where(company())->whereIn('status',[5])->orderBy('id', 'DESC')->paginate(25);
		}
        return view('bill.replacelist', compact('allBill'));
    }

    public function returnAll(){
		if(currentUser()=="salesman"){
        	$u=encryptor('decrypt', request()->session()->get('user'));
			$allBill = Bill::where(company())->where(branch())->where('userId',$u)->whereIn('status',[3,4])->orderBy('id', 'DESC')->paginate(25);
		}
		elseif(currentUser()=="salesmanager"){
			$allBill = Bill::where(company())->where(branch())->whereIn('status',[3,4])->orderBy('id', 'DESC')->paginate(25);
		}
		else{
			$allBill = Bill::where(company())->whereIn('status',[3,4])->orderBy('id', 'DESC')->paginate(25);
		}
        return view('bill.returnlist', compact('allBill'));
    }

    public function addForm(){
        $active_pack=UserPackage::where(company())->where(branch())->where('status',1)->sum('noofbill');
        $bill=Bill::where(company())->where(branch())->whereIn('status',[1])->count();
        
        if(!$active_pack){
            return redirect(route(currentUser().'.allBill'))->with($this->responseMessage(false, 'error', 'you don\'t have enough invoice. please buy a invoice package from Shop Managment -> Buy package'));
        }
        elseif($active_pack < $bill){
            return redirect(route(currentUser().'.allBill'))->with($this->responseMessage(false, 'error', 'you don\'t have enough invoice. please buy a invoice package from Shop Managment -> Buy package'));
        }else{
    		$company = Company::where(company())->orderBy('id', 'DESC')->first();
    		$allProduct = Product::where(company())->where(branch())->where('status', 1)->whereHas('stocks', function ($query) {
    						$query->where('stock','>','0');
    					})->orderBy('name', 'DESC')->get();
    		$allCustomer = Customer::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
    		$allCat = Category::where('status', 1)->orderBy('name', 'DESC')->get();
    		$allBrand = Brand::where('status', 1)->orderBy('name', 'DESC')->get();
    
    		return view('bill.add_new', compact([
    			'company','allProduct','allCustomer','allCat','allBrand'
    		]));
        }
    }
    
	public function setCustomer(Request $request){
	    
	    $customerExist = Customer::where(['custCode' => $request->custCode])->first();
		if($customerExist) {
			$error=0;
			$allCustomer = Customer::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
			$data="<option value=''>Select Customer</option>";
			if($allCustomer){
				foreach($allCustomer as $v){
				    if($customerExist->id==$v->id)
					    $data.="<option selected value='".$v->id."'>".$v->custCode." - ".$v->name."</option>";
					else
					    $data.="<option value='".$v->id."'>".$v->custCode." - ".$v->name."</option>";
				}
			}
			$allcust=array();
			if($allCustomer){
	  			foreach ($allCustomer as $cust){
		  			$allcust[$cust->id]=array(
		  				 	"name"=> $cust->name,
						   "address"=> $cust->address,
						   "contact_person"=> $cust->contact_person,
						   "contact_no_b"=> $cust->contact_no_b,
						   "custCode"=> $cust->custCode,
						   "email"=> $cust->email,
						   "zone"=> $cust->zone?$cust->zone->name:""
					  );
				}
			 }

			return json_encode(array("exitstCustomer" => true,"selected"=>$customerExist->id, "data"=>$data,"error"=>$error,"allCustomer"=>$allcust));
		}
		
	    $customer = new Customer;
        $customer->custCode 		= $request->custCode;
		$customer->name 			= $request->name;
		$customer->address 			= $request->address;
		$customer->contact_no_b 	= $request->contact_no_b;
		$customer->email 			= $request->email;
		$customer->state_id			= $request->state_id;
		$customer->zone_id			= $request->zone_id;
		$customer->userId 			= encryptor('decrypt', $request->userId);
		$customer->companyId 		= company()['companyId'];
		$customer->branchId         = 1;
		$customer->status 			= 1;
		$customer->created_at		= Carbon::now();
            
		if($customer->save()){
			$error=0;
			$allCustomer = Customer::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
			$data="<option value=''>Select Customer</option>";
			if($allCustomer){
				foreach($allCustomer as $v){
				    if($customer->id==$v['id'])
					    $data.="<option selected value='".$v->id."' class='".$v->type."'>".$v->custCode." - ".$v->name."</option>";
					else
					    $data.="<option value='".$v->id."' class='".$v->type."'>".$v->custCode." - ".$v->name."</option>";
				}
			}
			$allcust=array();
			if($allCustomer){
	  			foreach ($allCustomer as $cust){
		  			$allcust[$cust->id]=array(
		  				 	"name"=> $cust->name,
						   "address"=> $cust->address,
						   "contact_person"=> $cust->contact_person,
						   "contact_no_b"=> $cust->contact_no_b,
						   "custCode"=> $cust->custCode,
						   "email"=> $cust->email,
						   "zone"=> $cust->zone?$cust->zone->name:""
					  );
				}
			 }
			 return json_encode(array("exitstCustomer" => false,"selected"=>$customer->id, "data"=>$data,"error"=>$error,"allCustomer"=>$allcust));
		}
		else{
			echo json_encode(array("data"=>"","error"=>"Customer not saved."));
		}
    }
    
	public function getBatch(){
		$error=0;
		$id=$_GET['pid'];
        $medi=Stock::where(company())->where('productId',$id)->where('status',1)->get();
		$data="<option value=''>Select Batch</option>";
		if($medi){
			foreach($medi as $m){
				$data.="<option value='".$m->batchId.",".$m->stock.",".$m->mrpPrice.",".$m->retailPrice.",".$m->discount.",".$m->expiryDate.",".$m->id."'>".$m->batchId." Quantity - ".$m->stock."</option>";
			}
		}
		echo json_encode(array("data"=>$data,"error"=>$error));
    }
	
    public function store(NewBillRequest $request){
		try {
			DB::beginTransaction();
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
			$b->created_at = Carbon::now();

			if(!!$b->save()){
				foreach($request->products as $id => $details){
					if($details['product_id'] && $details['qty']){
    					$bi 			= new BillItem;
    					$bi->companyId 	= company()['companyId'];
    					$bi->branchId 	= branch()['branchId'];
    					$bi->bill_id 	= $b->id;
    					$bi->item_id 	= $details['product_id'];
						if($id==(count($request->products)-1)){
    						$bi->batchId 	= explode('-',$request->batchId)[0];
    						$bi->cost_price 	= explode('-',$request->batchId)[5];
						}else{
    						$bi->batchId 	= explode('-',$details['batchId'])[0];
    						$bi->cost_price 	= explode('-',$details['batchId'])[5];
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
                    		$medi=Stock::find(explode('-',$request->batchId)[4]);
						else
                    		$medi=Stock::find(explode('-',$details['batchId'])[4]);
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