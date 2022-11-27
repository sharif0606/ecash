<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Company;
use App\Models\Customer;
use App\Models\State;
use App\Models\Zone;
use Exception;
use View;

class ServiceController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        View::share('allState', $allState);
        View::share('allZone', $allZone);
    }
    public function index()
    {
        $company = Company::where(company())->orderBy('id', 'DESC')->first();
        $allServices = Service::orderBy('id', 'DESC')->get();
        return view('service.index', compact(['allServices', 'company']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addForm()
    {
        $company = Company::where(company())->orderBy('id', 'DESC')->first();
        $allCustomer = Customer::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
        /*echo '<pre>';
        print_r($allsellItems->toArray());
        echo '</pre>';
        die();*/
        return view('service.add_new', compact(['company','allCustomer']));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = new Service;
        $service->product_detl   = $request->product_detl;
        $service->imei   = $request->imei;
        $service->receive_date  = date('Y-m-d', strtotime($request->receive_date));
        $service->customer_id   = $request->customer_id;
        $service->userId 		= encryptor('decrypt', $request->userId);
		$service->companyId 	= company()['companyId'];
		$service->branchId      = branch()['branchId'];
        $service->save();
        return redirect(route(currentUser() . '.allService'))->with($this->responseMessage(true, null, 'Service Received'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $allserviceItems = Service::where('id', encryptor('decrypt', $id))->first();
        $company = Company::where(company())->orderBy('id', 'DESC')->first();
        $allCustomer = Customer::where(company())->where('status', 1)->orderBy('name', 'DESC')->get();
        return view('service.edit', compact([
            'allserviceItems', 'company','allCustomer'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $edit_service = Service::find(encryptor('decrypt', $request->id));
            $edit_service->product_detl   = $request->product_detl;
            $edit_service->imei   = $request->imei;
            $edit_service->receive_date = date('Y-m-d', strtotime($request->receive_date));
            $edit_service->customer_id   = $request->customer_id;
            $edit_service->save();
            return redirect(route(currentUser() . '.allService'))->with($this->responseMessage(true, null, 'Service updated'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }
}
