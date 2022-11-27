<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Http\Requests\State\NewStateRequest;
use App\Http\Requests\State\UpdateStateRequest;
use App\Http\Traits\ResponseTrait;

use Exception;
use Carbon\Carbon;

class StateController extends Controller
{
	use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allState = State::orderBy('id', 'DESC')->paginate(25);
        return view('state.index', compact('allState'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addForm()
    {
        return view('state.add_new');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewStateRequest $request)
    {
        try {
            $state = new State;
            $state->code = $request->stateCode;
            $state->name = $request->stateName;
            $state->created_at = Carbon::now();

            if(!!$state->save()) return redirect(route(currentUser().'.allState'))->with($this->responseMessage(true, null, 'Division created'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allState'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function editForm($name, $id)
    {
        $state = State::find(encryptor('decrypt', $id));
        return view('state.edit', compact(['state']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStateRequest $request)
    {
        try {
            $state = State::find(encryptor('decrypt', $request->id));
            $state->code = $request->stateCode;
            $state->name = $request->stateName;
            $state->updated_at = Carbon::now();

            if(!!$state->save()) return redirect(route(currentUser().'.allState'))->with($this->responseMessage(true, null, 'Division updated'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allState'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    public function delete($name, $id){
        try {
            $state = State::find(encryptor('decrypt', $id));
            if(!!$state->delete())
                return redirect(route(currentUser().'.allState'))->with($this->responseMessage(true, null, 'Division deleted'));
                
        }catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allState'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
