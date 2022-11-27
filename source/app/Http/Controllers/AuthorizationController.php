<?php

namespace App\Http\Controllers;

//use App\Lib\GoogleAuthenticator;
//use Auth;
use App\Http\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthorizationController extends Controller
{
	use ResponseTrait;
    public function __construct()
    {
        //return $this->activeTemplate = activeTemplate();
    }
    public function checkValidCode($user, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$user->ver_code_send_at) return false;
        if ($user->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($user->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm($id)
    {
		$user = User::find(encryptor('decrypt', $id));
		$mobileNumber = $user->mobileNumber;
		if (!$this->checkValidCode($user, $user->ver_code)) {
			$user->ver_code = verificationCode(6);
			$user->ver_code_send_at = Carbon::now();
			$user->save();
			$text = "Your OTP is ".$user->ver_code;
			sendSMS($mobileNumber, $text);
		}
		return view('authorization.sms',compact(array('user')));
	
	}

    public function sendVerifyCode(Request $request,$id)
    {
		$user = User::find(encryptor('decrypt', $request->id));
        if ($this->checkValidCode($user, $user->ver_code, 2)) {
            $target_time = $user->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
			return redirect(route('authorization',$request->id))->with($this->responseMessage(false, null, 'Please Try after ' . $delay . ' Seconds'));
        }
        if (!$this->checkValidCode($user, $user->ver_code)) {
            $user->ver_code = verificationCode(6);
            $user->ver_code_send_at = Carbon::now();
            $user->save();
        } else {
            $user->ver_code = $user->ver_code;
            $user->ver_code_send_at = Carbon::now();
            $user->save();
        }



        if ($request->type === 'email') {
            sendEmail($user, 'EVER_CODE',[
                'code' => $user->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
			sendSMS($user->mobileNumber, $user->ver_code);
			return redirect(route('authorization',$request->id))->with($this->responseMessage(true, null, 'OTP verification code sent successfully.'));
            /*$notify[] = ['success', 'SMS verification code sent successfully'];
            return back()->withNotify($notify);*/
        } else {
            throw ValidationException::withMessages(['resend' => 'Sending Failed']);
        }
    }

    public function emailVerification(Request $request)
    {
        $rules = [
            'email_verified_code.*' => 'required',
        ];
        $msg = [
            'email_verified_code.*.required' => 'Email verification code is required',
        ];
        $validate = $request->validate($rules, $msg);


        $email_verified_code =  str_replace(',','',implode(',',$request->email_verified_code));

        $user = Auth::user();

        if ($this->checkValidCode($user, $email_verified_code)) {
            $user->ev = 1;
            $user->ver_code = null;
            $user->ver_code_send_at = null;
            $user->save();
            return redirect()->intended(route('user.home'));
        }
        throw ValidationException::withMessages(['email_verified_code' => 'Verification code didn\'t match!']);
    }

    public function smsVerification(Request $request)
    {
        $request->validate([
            'sms_verified_code.*' => 'required',
        ], [
            'sms_verified_code.*.required' => 'SMS verification code is required',
        ]);

        $sms_verified_code =  str_replace(',','',implode(',',$request->sms_verified_code));
		$user = User::find(encryptor('decrypt', $request->id));
        if ($this->checkValidCode($user, $sms_verified_code)) {
			
            $user->sv = 1;
            $user->ver_code = null;
            $user->ver_code_send_at = null;
            $user->save();
            return redirect(route('signInForm'))->with($this->responseMessage(true, null, 'Mobile Number Verified Successfully'));
        }
		return redirect(route('authorization',$request->id))->with($this->responseMessage(true, null, 'Verification code didn\'t match!'));
    }
    public function g2faVerification(Request $request)
    {
        $user = auth()->user();

        $this->validate(
            $request, [
            'code.*' => 'required',
        ], [
            'code.*.required' => 'Code is required',
        ]);

        $ga = new GoogleAuthenticator();


        $code =  str_replace(',','',implode(',',$request->code));

        $secret = $user->tsc;
        $oneCode = $ga->getCode($secret);
        $userCode = $code;
        if ($oneCode == $userCode) {
            $user->tv = 1;
            $user->save();
            return redirect()->route('user.home');
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }
}
