<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\Branch\NewBranch;
use App\Http\Requests\Branch\UnpdateBranch;
use App\Http\Traits\ResponseTrait;
use App\Models\State;
use App\Models\Zone;
use Exception;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ResponseTrait;
    public function index()
    {
        $alldata= Branch::where(company())->orderBy('id', 'DESC')->paginate(25);
        return view('branch.index', compact('alldata'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function addForm(){
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        return view('branch.add_new', compact(['allState','allZone']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewBranch $request){
        try {
            $b 					= new Branch;
            $b->branch_name 	= $request->branch_name;
            $b->contact_number 	= $request->contact_number;
            $b->branch_email 	= $request->branch_email;
            $b->branch_add 		= $request->branch_add;
            $b->country 	    = $request->country;
            $b->state_id 	    = $request->state_id;
            $b->zone_id 		= $request->zone_id;
            $b->userId          = encryptor('decrypt', $request->userId);
			$b->companyId       = company()['companyId'];
            $b->status          = $request->status;
            if(!!$b->save()) return redirect(route(currentUser().'.allBranch'))->with($this->responseMessage(true, null, 'Branch created'));
        } catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function editForm($id)
    {
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        $data = Branch::find(encryptor('decrypt', $id));
        return view('branch.edit', compact(['data','allState','allZone']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(UnpdateBranch $request){
        try {
            $b = Branch::findOrFail(encryptor('decrypt', $request->id));
            $b->branch_name 	= $request->branch_name;
            $b->contact_number 	= $request->contact_number;
            $b->branch_email 	= $request->branch_email;
            $b->branch_add 		= $request->branch_add;
            $b->country 	    = $request->country;
            $b->state_id 	    = $request->state_id;
            $b->zone_id 		= $request->zone_id;
            $b->userId          = encryptor('decrypt', $request->userId);
            $b->status          = $request->status;

            if(!!$b->save()) return redirect(route(currentUser().'.allBranch'))->with($this->responseMessage(true, null, 'Branch updated'));
        } catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
