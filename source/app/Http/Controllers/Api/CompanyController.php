<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class CompanyController extends Controller
{
    use ImageHandleTraits;
    
    public function index($token){
        $data = User::select('id','companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $a = Company::where('companyId',$data->companyId)->first();
        if($a){
            $data=array(
                "id"=> $a->id,
                "company_name"=> $a->company_name,
                "company_slogan"=> $a->company_slogan,
                "contact_number"=> $a->contact_number,
                "company_email"=> $a->company_email,
                "company_add_a"=> $a->company_add_a,
                "company_add_b"=> $a->company_add_b,
                "billing_terms"=> $a->billing_terms,
                "currency"=> $a->currency,
                "currency_symble"=> $a->currency_symble,
                "invoice"=> $a->invoice,
                "tax"=> $a->tax,
                "tin"=> $a->tin,
                "webiste"=> $a->webiste,
                "facebook"=> $a->facebook,
                "twitter"=> $a->twitter,
                "company_logo"=> url('/').'/storage/images/company/'.$a->company_logo,
                "billing_seal"=> url('/').'/storage/images/company/'.$a->billing_seal,
                "billing_signature"=> url('/').'/storage/images/company/'.$a->billing_signature,
                "trade_l"=> url('/').'/storage/images/company/'.$a->trade_l,
                "status"=> $a->status,
                "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                );
            
        }
        
        return response()->json(array("company" => $data), 200);
    }

    public function update(Request $request,$token,$id){
        $data = User::select('id','companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $company = Company::find($id);
        
        if (!$company) {
            return response()->json(array('errors' => [0 => 'Id is not valid!']), 400);
            exit;
        }
        if ($company->companyId!=$data->companyId) {
            return response()->json(array('errors' => [0 => 'This is not your company']), 400);
            exit;
        }

        if($request->has('company_logo'))
            if($request->company_logo)
                if($this->deleteImage($company->company_logo, 'company'))
                    $company->company_logo = $this->uploadImage($request->file('company_logo'), 'company');
                else
                    $company->company_logo = $this->uploadImage($request->file('company_logo'), 'company');
			
        if($request->has('billing_seal'))
            if($request->billing_seal)
                if($this->deleteImage($company->billing_seal, 'company'))
                    $company->billing_seal = $this->uploadImage($request->file('billing_seal'), 'company');
                else
                    $company->billing_seal = $this->uploadImage($request->file('billing_seal'), 'company');
			
        if($request->has('billing_signature')) 
            if($request->billing_signature)
                if($this->deleteImage($company->billing_signature, 'company'))
                    $company->billing_signature = $this->uploadImage($request->file('billing_signature'), 'company');
                else
                    $company->billing_signature = $this->uploadImage($request->file('billing_signature'), 'company');
			
        if($request->has('trade_l')) 
            if($request->trade_l)
                if($this->deleteImage($company->trade_l, 'company/trade_l'))
                    $company->trade_l = $this->uploadImage($request->file('trade_l'), 'company/trade_l');
                else
                    $company->trade_l = $this->uploadImage($request->file('trade_l'), 'company/trade_l');
		
        $company->company_name = $request->company_name;
        $company->company_slogan = $request->company_slogan;
        $company->contact_number = $request->contact_number;
        $company->company_email = $request->company_email;
        $company->company_add_a = $request->company_add_a;
        $company->company_add_b = $request->company_add_b;
        $company->billing_terms = $request->billing_terms;
        $company->currency = $request->currency;
        $company->currency_symble = $request->currency_symble;
        $company->tax = $request->tax;
		$company->tin = $request->tin;
        $company->webiste = $request->webiste;
        $company->facebook = $request->facebook;
        $company->twitter = $request->twitter;
        $company->status = $request->status;

        if(!!$company->save())
            return response()->json(array("success" => 'Company updated'), 200);
    }
}
