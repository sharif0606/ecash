<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\UserDetail;
use App\Models\Company;
use App\Models\Branch;
use App\Models\User;
use App\Models\UserPackage;

use Exception;
use Carbon\Carbon;
// Request
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\newUserRequest;

class AuthenticationController extends Controller
{
    use ResponseTrait, ImageHandleTraits;
    public function signInForm(){
        return view('authentication.login');
    }

    public function signIn(LoginRequest $request){
        if(!!$this->validUser($request)){
			$user = $this->validUser($request);
			if(!$user->sv && $user->roleIdentity){
				//dd($user);
				return redirect(route('authorization',encryptor('encrypt', $user->userId)));
			}
            else
			return redirect(route($this->validUser($request)->roleIdentity.'Dashboard'))->with($this->responseMessage(true, null, 'Log In successed'));
		}
        else
            return redirect(route('signInForm'))->with($this->responseMessage(false, "error", 'Invalid emial or password.'));
    }

    public function signUpForm(){
        return view('authentication.register');
    }
	
	public function signUpStore(newUserRequest $request){
        try {
            $lastCreatedUser = User::take(1)->orderBy('id', 'desc')->first();
            $user = new User;
            $user->roleId = 7;
            $user->companyId = $lastCreatedUser->id + 1;
            $user->name = $request->fullName;
            $user->email = $request->email;
            $user->mobileNumber = $request->mobileNumber;
            $user->password = md5($request->password);
            $user->status = 1;
            $user->userCreatorId = 1;
            $user->created_at = Carbon::now();

            if(!!$user->save()){
				$userd = new UserDetail;
				$userd->userId = $user->id;
				
				if($request->has('photo')) $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');
				
				$userd->address = $request->address;
				$userd->nid = $request->nid;
				$userd->save();
				
				$com=new Company;
				$com->companyId=$user->companyId;
				$com->userId=$user->id;
				$com->save();
				
				$branch=new Branch;
				$branch->companyId=$user->companyId;
				$branch->userId=$user->id;
				$branch->state_id=1;
				$branch->zone_id=1;
				$branch->save();
				
				$userup = User::find($user->id);
                $userup->branchId = $branch->id;
                $userup->save();
            
				$up 			= new UserPackage;
                $up->companyId 	= $user->companyId;
                $up->branchId 	= $branch->id;
                $up->couponCode = "initial";
                $up->discount 	= 0;
                $up->packageId 	= 1;
                $up->price 		= 0;
                $up->noofbill 	= 10;
                $up->status 	= 1;
                $up->requestedBy= $user->id;
                $up->userId 	= $user->id;
				$up->save();
				
				return redirect(route('signInForm'))->with($this->responseMessage(true, null, 'Successfully Registered'));
			}
        } catch (Exception $e) {
            dd($e);
            return redirect(route('signUpForm'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    public function forgotForm(){
        return view('authentication.forgot');
    }

    public function forgotPassword(ForgotPasswordRequest $request){
		$user = User::where(['mobileNumber' => $request->mobileNumber, 'status' => 1])->first();
		if($user){
    		$user->forget_code = verificationCode(6);
    		$user->save();
    		$text = "Your OTP is ".$user->forget_code;
    		sendSMS($request->mobileNumber, $text);
    		
            if(!!$user) return redirect(route('resetPasswordForm'));
        }else return redirect()->back()->with($this->responseMessage(false, "error", "This Mobile Number: $request->mobileNumber not found"));
    }

    public function resetPasswordForm(){
        return view('authentication.reset-password');
    }

    public function resetPassword(ResetPasswordRequest $request){
        $user = User::where(['forget_code' => $request->forget_code, 'status' => 1])->first();
        if($user){
            $user->forget_code = null;
            $user->password = md5($request->password);
            if($user->save()) return redirect(route('signInForm'))->with($this->responseMessage(true, null, "Password reset successfully. Now you can login"));
        }else{
            return redirect()->back()->with($this->responseMessage(false, "error", "This OPT is incorrect."));
        }
    }
    
    public function signOut(){
        request()->session()->flush();
        return redirect(route('signInForm'))->with($this->responseMessage(true, "error", 'Successfully logout.'));
    }

    protected function validUser($request){
        return $this->varifyUser($request);
    }

    protected function varifyUser($request){
        $user = User::join('roles', 'roleId', '=', 'roles.id')
        ->leftJoin('user_details', 'users.id', '=', 'user_details.userId')
        ->leftJoin('companies', 'users.companyId', '=', 'companies.id')
        ->select("companies.shopCode","users.sv","users.id as userId","users.state_id","users.zone_id","users.email","users.mobileNumber","users.timezone","users.branchId as branchId","users.companyId as companyId", "roles.type as roleType", "roles.id as roleId", "user_details.photo", "roles.identity as roleIdentity")
        ->where(['users.mobileNumber' => $request->username, 'users.password' => md5($request->password), 'users.status' => 1])
        ->orWhere(function($query) use($request){
            $query->where(['users.email' => $request->username, 'users.password' => md5($request->password), 'users.status' => 1]);
        })->first();
        //dd($user);
        if(!isset($user->sv))
		    return $user;
		else
            !!$user && $this->setSession($user);
            
        return $user;
    }

    protected function setSession($user){
		if($user->photo)
			$photo=$user->photo;
		else
			$photo="dummy.png";
		
        return request()->session()->put(
        [
            'user' => encryptor('encrypt', $user->userId),
            'email' => encryptor('encrypt', $user->email),
            'mobileNumber' => encryptor('encrypt', $user->mobileNumber),
            'companyId' => encryptor('encrypt', $user->companyId),
            'branchId' => encryptor('encrypt', $user->branchId),
            'shopCode' =>encryptor('encrypt', $user->shopCode),
            'timezone' => encryptor('encrypt', $user->timezone),
            'roleId' => encryptor('encrypt', $user->roleId),
            'roleIdentity' => encryptor('encrypt', $user->roleIdentity),
            'state_id' => $user->state_id,
            'zone_id' => $user->zone_id,
            'uphoto' => $photo,
        ]);
    }
    
}
