<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\NewCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\State;
use App\Models\Zone;
use Exception;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	use ResponseTrait;
    public function index(){
        $allCustomer = Customer::where(company())->whereNotNull('state_id')->whereNotNull('zone_id')->orderBy('id', 'DESC')->paginate(25);
        return view('customer.index', compact('allCustomer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function addForm(){
        $allCustomer = Customer::select('id','name','custCode')->where(company())->whereNotNull('state_id')->whereNotNull('zone_id')->orderBy('name', 'ASC')->get();
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        return view('customer.add_new', compact(['allState','allZone','allCustomer']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewCustomerRequest $request){
        try {
            $customer = new Customer;
            $customer->custCode 		= $request->custCode;
            $customer->name 			= $request->name;
            $customer->address 			= $request->address;
            $customer->contact_person 	= $request->contact_person;
            $customer->contact_no_b 	= $request->contact_no_b;
            $customer->email 			= $request->email;
            $customer->state_id			= $request->state_id;
            $customer->zone_id			= $request->zone_id;
            $customer->userId           = encryptor('decrypt', $request->userId);
			$customer->companyId        = company()['companyId'];
			$customer->branchId         = branch()['branchId'];
            $customer->status           = $request->status;
            $customer->created_at       = Carbon::now();

            if(!!$customer->save()) return redirect(route(currentUser().'.allCustomer'))->with($this->responseMessage(true, null, 'Customer created'));

        } catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function editForm($id)
    {
        $allCustomer = Customer::select('id','name','custCode')->where(company())->whereNotNull('state_id')->whereNotNull('zone_id')->orderBy('name', 'ASC')->get();
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        $data = Customer::find(encryptor('decrypt', $id));
        return view('customer.edit', compact(['data','allState','allZone','allCustomer']));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request){
        try {
            $customer = Customer::findOrFail(encryptor('decrypt', $request->id));
            $customer->custCode 		= $request->custCode;
            $customer->name 			= $request->name;
            $customer->address 			= $request->address;
            $customer->contact_person 	= $request->contact_person;
            $customer->contact_no_b 	= $request->contact_no_b;
            $customer->email 			= $request->email;
            $customer->state_id			= $request->state_id;
            $customer->zone_id			= $request->zone_id;
            $customer->userId 			= encryptor('decrypt', $request->userId);
			$customer->companyId 		= company()['companyId'];
			$customer->branchId         = branch()['branchId'];
            $customer->status 			= $request->status;
            $customer->created_at		= Carbon::now();

            if(!!$customer->save()) return redirect(route(currentUser().'.allCustomer'))->with($this->responseMessage(true, null, 'Customer updated'));
        } catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $c = Customer::find(encryptor('decrypt', $id));
            if(!!$c->delete()){
                return redirect(route(currentUser().'.allCustomer'))->with($this->responseMessage(true, null, 'Customer deleted'));
            }
        }catch (Exception $e) {
            return redirect(route(currentUser().'.allCustomer'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
