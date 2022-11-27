<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\Supplier\NewSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\State;
use App\Models\Zone;
use Exception;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	use ResponseTrait;
    public function index(){
        $allSupplier = Supplier::where(company())->whereNotNull('state_id')->whereNotNull('zone_id')->orderBy('id', 'DESC')->paginate(25);
        return view('supplier.index', compact('allSupplier'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function addForm(){
        $allSupplier = Supplier::select('id','name','supCode')->where(company())->whereNotNull('state_id')->whereNotNull('zone_id')->orderBy('name', 'DESC')->get();
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        return view('supplier.add_new', compact(['allState','allZone','allSupplier']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewSupplierRequest $request){
        try {
            $supplier 					= new Supplier;
            $supplier->supCode 			= $request->supCode;
            $supplier->name 			= $request->name;
            $supplier->address 			= $request->address;
            $supplier->contact_person 	= $request->contact_person;
            $supplier->contact_no_b 	= $request->contact_no_b;
            $supplier->email 			= $request->email;
            $supplier->state_id			= $request->state_id;
            $supplier->zone_id			= $request->zone_id;
            $supplier->userId           = encryptor('decrypt', $request->userId);
			$supplier->companyId        = company()['companyId'];
			$supplier->branchId         = branch()['branchId'];
            $supplier->status           = $request->status;

            if(!!$supplier->save()) return redirect(route(currentUser().'.allSupplier'))->with($this->responseMessage(true, null, 'Supplier created'));

        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function editForm($id)
    {
        $allSupplier = Supplier::select('id','name','supCode')->where(company())->whereNotNull('state_id')->whereNotNull('zone_id')->orderBy('name', 'DESC')->get();
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        $data = Supplier::find(encryptor('decrypt', $id));
        return view('supplier.edit', compact(['data','allState','allZone']));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplierRequest $request){
        try {
            $supplier = Supplier::findOrFail(encryptor('decrypt', $request->id));
            $supplier->supCode 			= $request->supCode;
            $supplier->name 			= $request->name;
            $supplier->address 			= $request->address;
            $supplier->contact_person 	= $request->contact_person;
            $supplier->contact_no_b 	= $request->contact_no_b;
            $supplier->email 			= $request->email;
            $supplier->state_id			= $request->state_id;
            $supplier->zone_id			= $request->zone_id;
            $supplier->userId           = encryptor('decrypt', $request->userId);
			$supplier->companyId        = company()['companyId'];
			$supplier->branchId         = branch()['branchId'];
            $supplier->status           = $request->status;

            if(!!$supplier->save()) return redirect(route(currentUser().'.allSupplier'))->with($this->responseMessage(true, null, 'Supplier updated'));
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
    public function delete( $id)
    {
        try {
            $c = Supplier::find(encryptor('decrypt', $id));
            if(!!$c->delete()){
                return redirect(route(currentUser().'.allSupplier'))->with($this->responseMessage(true, null, 'Supplier deleted'));
            }
        }catch (Exception $e) {
            return redirect(route(currentUser().'.allSupplier'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
