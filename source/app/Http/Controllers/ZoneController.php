<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\Zone\NewZoneRequest;
use App\Http\Requests\Zone\UpdateZoneRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Zone;
use App\Models\State;
use Exception;
use Carbon\Carbon;

class ZoneController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allZone = Zone::orderBy('id', 'DESC')->paginate(25);
        return view('zone.index', compact('allZone'));
    }

    public function addForm(){
        $allState = State::orderBy('id', 'DESC')->get();
        return view('zone.add_new', compact('allState'));
    }

    public function store(NewZoneRequest $request){
        try {
            $zone = new zone;
            $zone->code = $request->zoneCode;
            $zone->name = $request->zoneName;
            $zone->stateId = $request->stateId;
            $zone->created_at = Carbon::now();

            if(!!$zone->save()) return redirect(route(currentUser().'.allZone'))->with($this->responseMessage(true, null, 'District created'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allZone'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
    public function editForm($name, $id){
        $zone = Zone::find(encryptor('decrypt', $id));
        $allState = State::orderBy('id', 'DESC')->get();
        return view('zone.edit', compact(['zone','allState']));
    }

    public function update(UpdateZoneRequest $request){
        try {
            $zone = Zone::find(encryptor('decrypt', $request->id));
            $zone->stateId = $request->stateId;
            $zone->name = $request->zoneName;
            $zone->code = $request->zoneCode;
            $zone->updated_at = Carbon::now();

            if(!!$zone->save()) return redirect(route(currentUser().'.allZone'))->with($this->responseMessage(true, null, 'District updated'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allZone'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
    public function delete($name, $id){
        try {

            $zone = Zone::find(encryptor('decrypt', $id));
            if(!!$zone->delete()){
                return redirect(route(currentUser().'.allZone'))->with($this->responseMessage(true, null, 'District deleted'));
            }

        }catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allZone'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
}
