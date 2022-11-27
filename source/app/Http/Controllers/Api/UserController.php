<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\UserDetail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

use App\Models\Branch;
use App\Models\Company;
use App\Models\State;
use App\Models\Zone;

use Exception;
use Carbon\Carbon;
use DB;


use App\Mail\TestEmail;
use Mail;

class UserController extends Controller{
    use ResponseTrait, ImageHandleTraits;

    public function index($token, $limit = 10, Request $request){
        
        $data = User::select('companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $page = $request->has('page') ? $request->get('page') : 1;
        
        if($data->roleId==7){
            $allUser = User::where([
                'companyId' => $data->companyId
            ])->with('role')->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
        }else{
            $allUser = User::where([
                'companyId' => $data->companyId,
                'branchId' => $data->branchId
            ])->with('role')->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
        }
        $d=array();
        if($allUser){
            foreach($allUser as $a){
                $d[]=array(
                        "id"=> $a->id,
                        "name"=> $a->name,
                        "email"=> $a->email,
                        "mobileNumber"=> $a->mobileNumber,
                        "roleId"=> $a->roleId,
                        "state_id"=> $a->state_id,
                        "zone_id"=> $a->zone_id,
                        "state"=> $a->state?$a->state->name:'',
                        "zone"=> $a->zone?$a->zone->name:'',
                        "companyId"=> $a->companyId,
                        "company"=> $a->company?$a->company->company_name:'',
                        "branchId"=> $a->branch?$a->branch->branch_name:'',
                        "status"=> $a->status,
                        "photo"=> "https://cashbaksho.com/storage/images/user/photo/".$a->details->photo,
                        "address"=> $a->details->address,
                        "nid"=> $a->details->nid,
                        "api_token"=> $a->api_token,
                        "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                        "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                    );
            }
        }
        return response()->json(array("allUser" => $d), 200);
    }

    public function rolelist($token)
    {
        $data = User::select('roleId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        if ($data->roleId==7) 
            $roles = Role::whereIn('identity', ['salesmanager'])->get();
        elseif ($data->roleId==8) 
            $roles = Role::whereIn('identity', ['salesman'])->get();
        
        return response()->json(array("allRole" => $roles), 200);
    }

    public function store($token, Request $request){
        
        $data = User::select('roleId','companyId','branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }

        $rules = ['mobileNumber' => 'required|unique:users','roleId' => 'required',
                    'email' => 'required|unique:users','password' => 'required',
                    'name' => 'required', 'status' => 'required',
                    'state_id' => 'required', 'zone_id' => 'required', 'branchId' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
        DB::beginTransaction();
        $user = new User;
        $user->roleId = $request->roleId;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobileNumber = $request->mobileNumber;
        $user->state_id = $request->state_id;
        $user->zone_id = $request->zone_id;
        $user->password = md5($request->password);
        $user->status = $request->status;
        $user->companyId = $data->companyId;
        $user->branchId = $request->branchId;
        $user->userCreatorId = $data->id;

        if (!!$user->save()) {
            $userd = new UserDetail;
            $userd->userId = $user->id;
            
            if ($request->has('photo')) $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');
            
            $userd->address = $request->address;
            $userd->nid = $request->nid;
            
            if(!!$userd->save()){
                DB::commit();
                return response()->json(array("success" => 'User created',"user"=>$user), 200);
            }else{
                DB::rollBack();
            }
        }
    }

    public function editForm($token, $id){
        
        $data = User::where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $a = User::find($id);
        $data=array();
        if($a){
            $data=array(
                "id"=> $a->id,
                "name"=> $a->name,
                "email"=> $a->email,
                "mobileNumber"=> $a->mobileNumber,
                "roleId"=> $a->roleId,
                "state_id"=> $a->state_id,
                "zone_id"=> $a->zone_id,
                "state"=> $a->state?$a->state->name:'',
                "zone"=> $a->zone?$a->zone->name:'',
                "companyId"=> $a->companyId,
                "company"=> $a->company?$a->company->company_name:'',
                "branchId"=> $a->branch?$a->branch->branch_name:'',
                "status"=> $a->status,
                "photo"=> "https://cashbaksho.com/storage/images/user/photo/".$a->details->photo,
                "address"=> $a->details->address,
                "nid"=> $a->details->nid
                );
        }
        return response()->json(array("userdata" => $data), 200);
    }

    public function update(Request $request,$id){
        
        $rules = ['mobileNumber' => "required|unique:users,mobileNumber, $id",'roleId' => 'required',
                    'email' => "required|unique:users,email, $id",
                    'name' => 'required', 'status' => 'required',
                    'state_id' => 'required', 'zone_id' => 'required', 'branchId' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
        DB::beginTransaction();
        $user = User::find($id);
        $user->roleId = $request->roleId;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobileNumber = $request->mobileNumber;
        $user->state_id = $request->state_id;
        $user->zone_id = $request->zone_id;
        
        if($request->password)
            $user->password = md5($request->password);
            
        $user->status = $request->status;
        
        if($request->branchId)
                $user->branchId = $request->branchId;

        if (!!$user->save()) {
            $userd = UserDetail::find($user->details->id);
            
            if ($request->has('photo') && $request->photo)
                    if ($this->deleteImage($userd->photo, 'user/photo'))
                        $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');
                    else
                        $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');
            if($request->address)
                $userd->address = $request->address;
            if($request->nid)
                $userd->nid = $request->nid;
                
            $userd->save();
            
            if(!!$userd->save()){
                DB::commit();
                return response()->json(array("success" => 'User updated',"user"=>$user), 200);
            }else{
                DB::rollBack();
            }
        }
    }

    public function userProfile($token){
        $a = User::where("api_token", $token)->first();
        $data=array();
        if($a){
            $data=array(
                "id"=> $a->id,
                "name"=> $a->name,
                "email"=> $a->email,
                "mobileNumber"=> $a->mobileNumber,
                "roleId"=> $a->roleId,
                "state_id"=> $a->state_id,
                "zone_id"=> $a->zone_id,
                "state"=> $a->state?$a->state->name:'',
                "zone"=> $a->zone?$a->zone->name:'',
                "companyId"=> $a->companyId,
                "company"=> $a->company?$a->company->company_name:'',
                "branchId"=> $a->branch?$a->branch->branch_name:'',
                "status"=> $a->status,
                "photo"=> "https://cashbaksho.com/storage/images/user/photo/".$a->details->photo,
                "address"=> $a->details->address,
                "nid"=> $a->details->nid,
                "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                );
        }
        return response()->json(array("userdata" => $data), 200);
    }
    
    public function changeprofile(Request $request,$token){
        $account = User::where("api_token", $token)->first();
        if (!$account) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $id=$account->id;
        $persoanl = UserDetail::where('userId', '=', $id)->first();
        
        $rules = ['name' => 'required','email' => "required|unique:users,email, $id",'mobileNumber' => "required|unique:users,mobileNumber, $id"];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
        try {
            if ($request->has('photo'))
                if ($this->deleteImage($persoanl->photo, 'user/photo'))
                    $persoanl->photo = $this->uploadImage($request->file('photo'), 'user/photo');
                else
                    $persoanl->photo = $this->uploadImage($request->file('photo'), 'user/photo');

            $persoanl->nid = $request->nid;
            $persoanl->address = $request->address;

            $account->name = $request->name;
            $account->mobileNumber = $request->mobileNumber;
            $account->save();

            if (!!$persoanl->save())
                return response()->json(array("success" => 'Profile Information updated'), 200);
        } catch (Exception $e) {
            return response()->json(array('errors' => [0 => 'Please try again!']), 400);
            return false;
        }
    }
    
    public function changePass(Request $request, $token)
    {
        $pass = User::where("api_token", $token)->first();
        if (!$pass) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $rules = ['password' => 'required','oldpass' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
        try {
            if ($pass['password'] == md5($request->oldpass)) {
                $pass->password = md5($request->password);
                if (!!$pass->save())
                    return response()->json(array("success" => 'Password updated'), 200);
            } else {
                return response()->json(array('errors' => [0 => 'Old Password Mismathed!']), 400);
            }
        } catch (Exception $e) {
            return response()->json(array('errors' => [0 => 'Please try again!']), 400);
            return false;
        }
    }
    
    
}
