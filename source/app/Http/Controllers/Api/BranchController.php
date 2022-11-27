<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index($token, $limit = 10, Request $request)
    {
        $data = User::select('companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $companyId = $data['companyId'];
        $page = $request->has('page') ? $request->get('page') : 1;
        
        $allBranch = Branch::where('companyId', $companyId)
            ->latest()->limit($limit)->offset(($page - 1) * $limit)
            ->get();
        $data=array();
        if($allBranch){
            foreach($allBranch as $a){
                $data[]=array(
                    "id"=> $a->id,
                    "branch_name"=> $a->branch_name,
                    "contact_number"=> $a->contact_number,
                    "branch_email"=> $a->branch_email,
                    "branch_add"=> $a->branch_add,
                    "state_id"=> $a->state_id,
                    "state"=> $a->state->name,
                    "zone_id"=> $a->zone_id,
                    "zone"=> $a->zone->name,
                    "companyId"=> $a->companyId,
                    "userId"=> $a->userId,
                    "status"=> $a->status,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                    );
            }
        }
        
        return response()->json(array("allBranch" => $data), 200);
    }

    
    public function store($token, Request $request)
    {
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $rules = ['branch_name' => 'required', 'contact_number' => 'required', 'status' => 'required', 'state_id' => 'required', 'zone_id' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
        $b 					= new Branch;
        $b->branch_name 	= $request->branch_name;
        $b->contact_number 	= $request->contact_number;
        $b->branch_email 	= $request->branch_email;
        $b->branch_add 		= $request->branch_add;
        $b->country 	    = "Bangladesh";
        $b->state_id 	    = $request->state_id;
        $b->zone_id 		= $request->zone_id;
        $b->userId          = $data['id'];
		$b->companyId       = $data['companyId'];
        $b->status          = $request->status;
        if(!!$b->save()) 
            return response()->json(array("success" => 'Branch created'), 200);
    }
    
    public function editForm($token, $id)
    {
        $data = User::where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $data = Branch::find($id);
        return response()->json(array("data" => $data), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $rules = ['branch_name' => 'required', 'contact_number' => 'required', 'status' => 'required', 'state_id' => 'required', 'zone_id' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $b = Branch::findOrFail($id);
        $b->branch_name 	= $request->branch_name;
        $b->contact_number 	= $request->contact_number;
        $b->branch_email 	= $request->branch_email;
        $b->branch_add 		= $request->branch_add;
        $b->state_id 	    = $request->state_id;
        $b->zone_id 		= $request->zone_id;
        $b->userId          = encryptor('decrypt', $request->userId);
        $b->status          = $request->status;

        if(!!$b->save()) 
            return response()->json(array("success" => 'Branch updated'), 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
