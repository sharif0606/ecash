<?php

namespace App\Http\Controllers;
use App\Http\Requests\Company\NewCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Company;
use Exception;
use Carbon\Carbon;

class CompanyController extends Controller
{
    use ResponseTrait, ImageHandleTraits;
    
    public function index(){
        $allCompany = Company::where(company())->orderBy('id', 'DESC')->first();
		if($allCompany)
			return view('company.index', compact('allCompany'));
		else
			return view('company.add_new');
    }

    public function addForm(){
        return view('company.add_new');
    }

    public function store(newCompanyRequest $request){
        try {
            $company = new Company;
            if($request->has('company_logo')) $company->company_logo = $this->uploadImage($request->file('company_logo'), 'company');
            if($request->has('billing_seal')) $company->billing_seal = $this->uploadImage($request->file('billing_seal'), 'company');
            if($request->has('billing_signature')) $company->billing_signature = $this->uploadImage($request->file('billing_signature'), 'company');
			if($request->has('trade_l')) $company->trade_l = $this->uploadImage($request->file('trade_l'), 'company/trade_l');
			
            $company->company_name = $request->company_name;
            $company->company_slogan = $request->company_slogan;
            $company->contact_number = $request->contact_number;
            $company->company_email = $request->company_email;
            $company->company_add_a = $request->company_add_a;
            $company->company_add_b = $request->company_add_b;
            $company->billing_terms = $request->billing_terms;
            $company->currency = $request->currency;
            $company->currency_symble = $request->currency_symble;
            $company->invoice = $request->invoice;
            $company->tax = $request->tax;
			$company->tin = $request->tin;
            $company->webiste = $request->webiste;
            $company->facebook = $request->facebook;
            $company->twitter = $request->twitter;
            $company->status = $request->status;
            $company->userId = encryptor('decrypt', $request->userId);
            $company->companyId = company()['companyId'];
            $company->created_at = Carbon::now();

            if(!!$company->save()) return redirect(route(currentUser().'.allCompany'))->with($this->responseMessage(true, null, 'Company created'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allCompany'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    public function editForm($name, $id){
        $category = Category::find(encryptor('decrypt', $id));
        return view('category.edit', compact(['category']));
    }

    public function update(updateCompanyRequest $request){
        try {
            $company = Company::find(encryptor('decrypt', $request->id));

            if($request->has('company_logo')) 
                if($this->deleteImage($company->company_logo, 'company'))
                    $company->company_logo = $this->uploadImage($request->file('company_logo'), 'company');
                else
                    $company->company_logo = $this->uploadImage($request->file('company_logo'), 'company');
				
            if($request->has('billing_seal')) 
                if($this->deleteImage($company->billing_seal, 'company'))
                    $company->billing_seal = $this->uploadImage($request->file('billing_seal'), 'company');
                else
                    $company->billing_seal = $this->uploadImage($request->file('billing_seal'), 'company');
				
            if($request->has('billing_signature')) 
                if($this->deleteImage($company->billing_signature, 'company'))
                    $company->billing_signature = $this->uploadImage($request->file('billing_signature'), 'company');
                else
                    $company->billing_signature = $this->uploadImage($request->file('billing_signature'), 'company');
				
            if($request->has('trade_l')) 
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
            $company->invoice = $request->invoice;
            $company->tax = $request->tax;
			$company->tin = $request->tin;
            $company->webiste = $request->webiste;
            $company->facebook = $request->facebook;
            $company->twitter = $request->twitter;
            $company->status = $request->status;
            $company->updated_at = Carbon::now();

            if(!!$company->save()) return redirect(route(currentUser().'.allCompany'))->with($this->responseMessage(true, null, 'Company updated'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allCompany'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
