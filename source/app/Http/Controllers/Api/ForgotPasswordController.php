<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showLinkRequestForm()
    {
        $page_title = "Forgot Password";
        $content    = Frontend::where('data_keys', 'sign_in.content')->first();
        return view(activeTemplate() . 'user.auth.passwords.email', compact('page_title', 'content'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $rules = [
            'username' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()),400);
        }
            
        $user = User::where('username', $request->username)->first();

        if (!$user) {
           return response()->json(array('errors' => [ 0 => 'User not found.' ]),400);
        }

        PasswordReset::where('email', $user->email)->delete();
        $code = verificationCode(6);
        $password = new PasswordReset();
        $password->email = $user->email;
        $password->token = $code;
        $password->created_at = \Carbon\Carbon::now();
        $password->save();

        $userIpInfo = getIpInfo();
        $userBrowserInfo = osBrowser();
        sendEmail($user, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => @$userBrowserInfo['os_platform'],
            'browser' => @$userBrowserInfo['browser'],
            'ip' => @$userIpInfo['ip'],
            'time' => @$userIpInfo['time']
        ]);

        $code = $code;// put this data on session
        $token = $user->api_token;// put this data on session
        return response()->json(array('token'=>$token,'code'=>$code,'success' => 'Password reset email sent successfully.' ),200);
    }

    public function codeVerify(){
        $page_title = 'Account Recovery';
        $email = session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error','Opps! session expired'];
            return redirect()->route('user.password.request')->withNotify($notify);
        }

        $content    = Frontend::where('data_keys', 'sign_in.content')->first();
        return view(activeTemplate().'user.auth.passwords.code_verify',compact('page_title','email', 'content'));
    }

    public function verifyCode($token,Request $request)
    { 
        $user=User::where('api_token',$token)->first();
        if(!$user)
        return response()->json(array('errors' => [ 0 => 'Token is not valid!' ]),400);
        
        $rules = ['code.*' => 'required','password' => 'required|min:5|confirmed'];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()),400);
        }
        
        //$code =  str_replace(',','',implode(',',$request->code));
        $code = $request->code;

        if (PasswordReset::where('token', $code)->where('email', $user->email)->count() != 1) {
            return response()->json(array('errors' => [ 0 => 'Invalid token' ]),400);
        }else{
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            return response()->json(array('success' => 'Password Changes successfully.' ),200);
        }
        // Remmber to ask flash email session data will remove or not
        //return response()->json(array('success' => 'You can change your password.' ),200);
    }

}
