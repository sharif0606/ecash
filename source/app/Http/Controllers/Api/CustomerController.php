<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\State;
use App\Models\Zone;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($token, $limit = 10, Request $request)
    {
        $data = User::select('companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $companyId = $data['companyId'];
        $branchId  = $data['branchId'];
        $page = $request->has('page') ? $request->get('page') : 1;
        $allCustomer = Customer::where('companyId', $companyId)->where('branchId', $branchId)->whereNotNull('state_id')->whereNotNull('zone_id')
            ->latest()->limit($limit)->offset(($page - 1) * $limit)
            ->get();
        $data=array();
        if($allCustomer){
            foreach($allCustomer as $a){
                $data[]=array(
                    "id"=> $a->id,
                    "custCode"=> $a->custCode,
                    "name"=> $a->name,
                    "address"=> $a->address,
                    "contact_person"=> $a->contact_person,
                    "contact_no_b"=> $a->contact_no_b,
                    "email"=> $a->email,
                    "type"=> $a->type,
                    "state_id"=> $a->state_id,
                    "state"=> $a->state->name,
                    "zone_id"=> $a->zone_id,
                    "zone"=> $a->zone->name,
                    "companyId"=> $a->companyId,
                    "branchId"=> $a->branchId,
                    "userId"=> $a->userId,
                    "status"=> $a->status,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                    );
            }
        }
        return response()->json(array("allCustomer" => $data), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($token, Request $request)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }

        $rules = ['custCode' => 'required', 'name' => 'required', 'status' => 'required', 'state_id' => 'required', 'zone_id' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }

        $customer = new Customer;
        $customer->custCode         = $request->custCode;
        $customer->name             = $request->name;
        $customer->address          = $request->address;
        $customer->contact_person   = $request->contact_person;
        $customer->contact_no_b     = $request->contact_no_b;
        $customer->email            = $request->email;
        $customer->state_id         = $request->state_id;
        $customer->zone_id          = $request->zone_id;
        $customer->userId           = $data['id'];
        $customer->companyId        = $data['companyId'];
        $customer->branchId         = $data['branchId'];
        $customer->status           = $request->status;

        if (!!$customer->save())
            return response()->json(array("success" => 'Customer created','data'=>$customer), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);
    }
    
    public function editForm($token, $id)
    {
        $data = User::where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $data = Customer::find($id);
        return response()->json(array("data" => $data), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id )
    {

        $rules = ['custCode' => 'required', 'name' => 'required','status' => 'required', 'state_id' => 'required', 'zone_id' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
        $customer = Customer::findOrFail($id);
        $customer->custCode         = $request->custCode;
        $customer->name             = $request->name;
        $customer->address          = $request->address;
        $customer->contact_person   = $request->contact_person;
        $customer->contact_no_b     = $request->contact_no_b;
        $customer->email            = $request->email;
        $customer->state_id         = $request->state_id;
        $customer->zone_id          = $request->zone_id;
        $customer->status           = $request->status;

        if (!!$customer->save())
            return response()->json(array("success" => 'Customer Updated','data'=>$customer), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
