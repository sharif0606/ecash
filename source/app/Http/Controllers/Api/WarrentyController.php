<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use App\Models\Warrenty;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Customer;
use App\Models\State;
use App\Models\Zone;
use App\Models\User;
use Exception;
use View;
use Illuminate\Support\Facades\Validator;
class WarrentyController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        View::share('allState', $allState);
        View::share('allZone', $allZone);
    }
    public function index($token, $limit = 10, Request $request)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $page = $request->has('page') ? $request->get('page') : 1;
        $allWarrenties = Warrenty::where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->where('userId',$data->id)->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
        return response()->json(array("allWarrenties" => $allWarrenties), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addForm($token)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $company = Company::where(company())->orderBy('id', 'DESC')->first();
        $allCustomer = Customer::where('companyId', $data->companyId)->orderBy('name', 'DESC')->get();
        $exis_bill_id = Warrenty::select('bill_id')->where('companyId', $data->companyId)->orderBy('id', 'DESC')->get();
        $allsellItems = Bill::select('bills.*')->where('companyId', $data->companyId)->where('status', 1)
            ->whereNotIn('id', $exis_bill_id)
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json(array("allsellItems" => $allsellItems,"allCustomer"=>$allCustomer), 200);
    }
    public function productDetails(Request $request)
    {
        if (company()['companyId']) {
            $company_id = company()['companyId'];
        }

        $allsellItems = BillItem::select('products.*')
            ->join('products', 'bill_items.item_id', '=', 'products.id')
            ->where('bill_items.companyId', '=', $company_id)
            ->where('bill_items.id', '=', $request->get('bill_id'))
            ->get();
        return response()->json($allsellItems);
    }
    
 public function customerDetails(Request $request)
    {
        if (company()['companyId']) {
            $company_id = company()['companyId'];
        }
        $customerbyinvNo = Bill::select('customers.*')
            ->join('customers', 'customers.id', '=', 'bills.customer_id')
            ->where('bills.companyId', '=', $company_id)
            ->where('bills.id', '=', $request->get('bill_id'))
            ->get();
        return response()->json($customerbyinvNo);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($token,Request $request)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $rules = ['bill_id' => 'required', 'item_id' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $warrenty = new Warrenty;
        $warrenty->bill_id = $request->bill_id;
        $warrenty->item_id = $request->item_id;
        $warrenty->receive_date = date('Y-m-d', strtotime($request->receive_date));
        $warrenty->claimer_type	= $request->claimer_type;
        $warrenty->customer_id	= ($request->claimer_type==2)?$request->customer_id:null;
        $warrenty->userId 		= $data['id'];
		$warrenty->companyId 	= $data['companyId'];
		$warrenty->branchId     = $data['branchId'];
        $warrenty->save();
        if (!!$warrenty->save())
            return response()->json(array("success" => 'Warrenty Received','data'=>$warrenty), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function show($token,$id)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $allwarrenty = Warrenty::find($id);
        $allCustomer = Customer::where('companyId', $data->companyId)->orderBy('id', 'DESC')->first();
        $allsellItems = Bill::where('companyId', $data->companyId)->orderBy('id', 'DESC')->get();
        return response()->json(array("allwarrenty" => $allwarrenty,'allCustomer' => $allCustomer,'allsellItems' => $allsellItems), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function edit(warrenty $warrenty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function update($token,Request $request,$id)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }

        $edit_warrenty = Warrenty::find($id);
        $edit_warrenty->receive_date = date('Y-m-d', strtotime($request->receive_date));
        $edit_warrenty->save();
        if (!!$edit_warrenty->save())
            return response()->json(array("success" => 'Warrenty Updated','data'=>$edit_warrenty), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);     

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function destroy(warrenty $warrenty)
    {
        //
    }
}
