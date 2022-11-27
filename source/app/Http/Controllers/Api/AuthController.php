<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    use ResponseTrait, ImageHandleTraits;
    public function login(Request $request)
    {
        //--- Validation Section
        $rules = ['email' => 'required', 'password' => 'required'];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        //--- Validation Section Ends
        $user = $request->email;
        $pass = $request->password;
        $userData = '';
        if ($user && $pass) {
            $userData = User::join('roles', 'roleId', '=', 'roles.id')->leftJoin('user_details', 'users.id', '=', 'user_details.userId')
                ->select("users.id as userId", "users.name as name", "users.timezone", "users.companyId as companyId", "roles.type as roleType", "roles.id as roleId", "user_details.photo", "roles.identity as roleIdentity")
                ->where(['users.email' => $user, 'users.password' => md5($pass), 'users.status' => 1])
                ->first();
        }
        if ($userData) {
            $token = $this->tokenGen($userData['userId']);
            if ($userData->photo)
                $photo = asset("storage/images/user/photo/" . $userData->photo);
            else
                $photo = asset("assets/media/users/dummy.png");

            $rdata = array(
                'id' => $userData->userId,
                'user' => $userData->name,
                'companyId' => $userData->companyId,
                'branchId' => $userData->branchId,
                'roleId' => $userData->roleId,
                'roleIdentity' => $userData->roleIdentity,
                'uphoto' => $photo,
                'token'  => $token,
            );
        } else {
            $rdata = array(
                'error' => "user name or password is not correct",
            );
        }
        return response()->json($rdata, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function register(Request $request)
    {
        $rules = ['fullName' => 'required', 'email' => 'required|unique:users', 'mobileNumber' => 'required|unique:users', 'password' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $lastCreatedUser = User::take(1)->orderBy('id', 'desc')->first();
        $user = new User;
        $user->roleId = 7;
        $user->companyId = $lastCreatedUser->id + 1;
        $user->branchId = $lastCreatedUser->id + 1;
        $user->name = $request->fullName;
        $user->email = $request->email;
        $user->mobileNumber = $request->mobileNumber;
        $user->password = md5($request->password);
        $user->status = 1;
        $user->userCreatorId = 1;
        $user->created_at = Carbon::now();

        if (!!$user->save()) {
            $userd = new UserDetail;
            $userd->userId = $user->id;

            if ($request->has('photo')) $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');

            $userd->address = $request->address;
            $userd->nid = $request->nid;
            $userd->save();
				
				$com=new Company;
				$com->companyId=$user->companyId;
				$com->userId=$user->id;
				$com->save();

            $authtoken = $this->tokenGen($user->id);
            return response()->json(array('success' => 1, 'message' => 'Successfully created account', 'token' => $authtoken), 200);
        }
    }

    public function tokenGen($id)
    {
        $ts = User::findOrFail($id);
        /* check if already has tocken or not */
        if ($ts->api_token) {
            $ts->save();
            return $ts->api_token;
        } else {
            $token = Str::random(8) . $id . Str::random(10);
            $ts->api_token = $token;
            $ts->save();
            return $token;
        }
    }
    
    public function forgotPassword(Request $request){
        $rules = ['mobileNumber' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
		$user = User::where(['mobileNumber' => $request->mobileNumber, 'status' => 1])->first();
		if($user){
    		$user->forget_code = verificationCode(6);
    		$user->save();
    		$text = "Your OTP is ".$user->forget_code;
    		sendSMS($request->mobileNumber, $text);
    		
            if(!!$user) return response()->json(array('success' => 1, 'message' => 'OTP sent'), 200);
		}
        else return response()->json(array('errors' => "This Mobile Number: ".$request->mobileNumber." not found"), 400);
    }

    public function resetPassword(Request $request){
        $rules = ['otp' => 'required', 'password' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $user = User::where(['forget_code' => $request->otp, 'status' => 1])->first();
        if($user){
            $user->forget_code = null;
            $user->password = md5($request->password);
            if($user->save()) return response()->json(array('success' => 1, 'message' => 'Password reset successfully. Now you can login'), 200);
        }else
            return response()->json(array('errors' => "This OPT is incorrect."), 400);
    }
}
