<?php

namespace App\Http\Controllers;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\Role;
use Exception;
use Carbon\Carbon;

class RoleController extends Controller
{
    use ResponseTrait;
    
    public function index(){
        $roles = Role::get();
        return view('role.index', compact('roles'));
    }

    public function addRoleForm(){
        return view('role.add-new');
    }

    public function store(Request $request){
        try {
            $role = new Role;
            $role->type = $request->roleType;
            $role->identity = strtolower(str_replace(" ", "", $request->roleType));
            $role->created_at = Carbon::now();

            if(!!$role->save()) return redirect(route('allRole'))->with($this->responseMessage(true, null, 'Role created'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route('allRole'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function editForm($name, $id){
        $role = Role::find(encryptor('decrypt', $id));
        return view('role.edit', compact(['role']));
    }

    public function update(Request $request){
        
        try {
            $role = Role::find(encryptor('decrypt', $request->id));
            $role->type = $request->roleType;
            $role->updated_at = Carbon::now();

            if(!!$role->save()) return redirect(route('allRole'))->with($this->responseMessage(true, null, 'Role updated'));

        } catch (Exception $e) {
            dd($e);
            return redirect(route('allRole'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function delete($name, $id){
        
        try {

            $role = Role::find(encryptor('decrypt', $id));
            if(!!$role->delete()){
                return redirect(route('allRole'))->with($this->responseMessage(true, null, 'Role deleted'));
            }

        }catch (Exception $e) {
            dd($e);
            return redirect(route('allRole'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
}
