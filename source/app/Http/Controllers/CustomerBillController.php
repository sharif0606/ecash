<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomerBillController extends Controller
{
    use ResponseTrait;
    public function checkValidCode($user, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$user->ver_code_send_at) return false;
        if ($user->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($user->ver_code !== $code) return false;
        return true;
    }
    public function index(){
        return view('customer-bill.phone');
    }
    

    public function authorizeForm(Request $request)
    {
		$user = Customer::where('custCode',$request->mobileNumber)->first();

		if (!$this->checkValidCode($user, $user->ver_code)) {
			$user->ver_code = verificationCode(6);
			$user->ver_code_send_at = Carbon::now();
			$user->save();
			$text = "Your OTP is ".$user->ver_code;
			//sendSMS($mobileNumber, $text);
		}
		return view('customer-bill.sms',compact('user'),['successMsg'=>'OTP verification code sent successfully.']);
	}
	
    public function sendVerifyCode(Request $request,$id)
    {
		$user = Customer::where('custCode',$id)->first();
        if ($this->checkValidCode($user, $user->ver_code, 2)) {
            $target_time = $user->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
			return view('customer-bill.sms',compact('user'),['successMsg'=>'Please Try after ' . $delay . ' Seconds']);
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
			//sendSMS($user->mobileNumber, $user->ver_code);
			return view('customer-bill.sms',compact('user'),['successMsg'=>'OTP verification code sent successfully.']);
            /*$notify[] = ['success', 'SMS verification code sent successfully'];
            return back()->withNotify($notify);*/
        } else {
            throw ValidationException::withMessages(['resend' => 'Sending Failed']);
        }
    }
    
    
    public function smsVerification(Request $request)
    {
        $request->validate([
            'sms_verified_code.*' => 'required',
        ], [
            'sms_verified_code.*.required' => 'SMS verification code is required',
        ]);

        $sms_verified_code =  str_replace(',','',implode(',',$request->sms_verified_code));
		$user = Customer::where('custCode',$request->mobileNumber)->first();
		//dd($request->all());
        if ($this->checkValidCode($user, $sms_verified_code)) {
            $user->ver_code = null;
            $user->ver_code_send_at = null;
            $user->save();
            return redirect(route('bill',$user->custCode))->with($this->responseMessage(true, null, 'Mobile Number Verified Successfully'));
        }
        return view('customer-bill.sms',compact('user'),['successMsg'=>'erification code didn\'t match!']);
    }
    public function customerBill($custCode){
        echo $custCode;
    }
    
}