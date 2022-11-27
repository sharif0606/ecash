<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Company;
use App\Models\Customer;
use App\Models\State;
use App\Models\Zone;
use App\Models\User;

use Exception;
use View;

use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use ResponseTrait;

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
        $allServices = Service::where('companyId', $data->companyId)->where('branchId', $data->branchId)
			                ->where('userId',$data->id)->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
        return response()->json(array("allServices" => $allServices), 200);
    }

    public function addForm($token)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $allCustomer = Customer::where('companyId', $data->companyId)->where('status', 1)->orderBy('name', 'DESC')->get();
        return response()->json(array("allCustomer" => $allCustomer), 200);
    }

    public function store($token,Request $request)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $rules = ['product_detl' => 'required', 'customer_id' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $service = new Service;
        $service->product_detl  = $request->product_detl;
        $service->imei          = $request->imei;
        $service->receive_date  = date('Y-m-d', strtotime($request->receive_date));
        $service->customer_id   = $request->customer_id;
        $service->userId 		= $data['id'];
		$service->companyId 	= $data['companyId'];
		$service->branchId      = $data['branchId'];
        $service->save();
        if (!!$service->save())
            return response()->json(array("success" => 'Service created','data'=>$service), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);
    }

    public function show($token,$id)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $allservice = Service::find($id);
        $allCustomer = Customer::where('companyId', $data->companyId)->orderBy('id', 'DESC')->first();
        return response()->json(array("allservice" => $allservice,'allCustomer' => $allCustomer), 200);
    }

    public function edit(Service $service)
    {
    }

    public function update($token,Request $request,$id)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $rules = ['product_detl' => 'required', 'customer_id' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $edit_service = Service::findOrFail($id);
        $edit_service->product_detl   = $request->product_detl;
        $edit_service->imei   = $request->imei;
        $edit_service->receive_date = date('Y-m-d', strtotime($request->receive_date));
        $edit_service->customer_id   = $request->customer_id;
        $edit_service->save();

        if (!!$edit_service->save())
            return response()->json(array("success" => 'Service Updated','data'=>$edit_service), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);
    }

    public function destroy(Service $service)
    {

    }
}
