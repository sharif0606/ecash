<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Warrenty;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Customer;
use App\Models\State;
use App\Models\Zone;
use Exception;
use View;

class WarrentyController extends Controller
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
        $allWarrenties = Warrenty::orderBy('id', 'DESC')->get();
        return view('warrenty.index', compact(['allWarrenties', 'company']));
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
        $exis_bill_id = Warrenty::select('bill_id')->orderBy('id', 'DESC')->get();
        $allsellItems = Bill::select('bills.*')
            ->whereNotIn('id', $exis_bill_id)
            ->orderBy('id', 'DESC')
            ->get();
        /*echo '<pre>';
        print_r($allsellItems->toArray());
        echo '</pre>';
        die();*/
        return view('warrenty.add_new', compact(['allsellItems', 'company','allCustomer']));
    }
    public function productDetails(Request $request)
    {
        if (company()['companyId']) {
            $company_id = company()['companyId'];
        }

        $allsellItems = BillItem::select('products.*')
            ->join('products', 'bill_items.item_id', '=', 'products.id')
            ->where('bill_items.companyId', '=', $company_id)
            ->where('bill_items.id', '=', $request->get('bill_id'))
            ->get();
        return response()->json($allsellItems);
    }
    
 public function customerDetails(Request $request)
    {
        if (company()['companyId']) {
            $company_id = company()['companyId'];
        }
        $customerbyinvNo = Bill::select('customers.*')
            ->join('customers', 'customers.id', '=', 'bills.customer_id')
            ->where('bills.companyId', '=', $company_id)
            ->where('bills.id', '=', $request->get('bill_id'))
            ->get();
        return response()->json($customerbyinvNo);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->toArray());
        $warrenty = new Warrenty;
        $warrenty->bill_id = $request->bill_id;
        $warrenty->item_id = $request->item_id;
        $warrenty->receive_date = date('Y-m-d', strtotime($request->receive_date));
        $warrenty->claimer_type	= $request->claimer_type;
        $warrenty->customer_id	= ($request->claimer_type==2)?$request->customer_id:null;
        $warrenty->userId 		= encryptor('decrypt', $request->userId);
		$warrenty->companyId 	= company()['companyId'];
		$warrenty->branchId     = branch()['branchId'];
        $warrenty->save();
        return redirect(route(currentUser() . '.allWarrenty'))->with($this->responseMessage(true, null, 'Warrenty Received'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Warrenty::where('id', encryptor('decrypt', $id))->first();
        $company = Company::where(company())->orderBy('id', 'DESC')->first();
        $allsellItems = Bill::orderBy('id', 'DESC')->get();
        return view('warrenty.edit', compact([
            'data', 'company', 'allsellItems'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function edit(warrenty $warrenty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $edit_warrenty = Warrenty::find(encryptor('decrypt', $request->id));
            $edit_warrenty->receive_date = date('Y-m-d', strtotime($request->receive_date));
            $edit_warrenty->save();
            return redirect(route(currentUser() . '.allWarrenty'))->with($this->responseMessage(true, null, 'Warrenty updated'));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\warrenty  $warrenty
     * @return \Illuminate\Http\Response
     */
    public function destroy(warrenty $warrenty)
    {
        //
    }
}
