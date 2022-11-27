<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\User;

use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    use ImageHandleTraits;

    public function index($token, $limit = 10, Request $request)
    {
        $data = User::select('companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $companyId = $data['companyId'];
        $page = $request->has('page') ? $request->get('page') : 1;
        
        $allBrand = Brand::where('companyId', $companyId)->orWhereNull('companyId')->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
        $data=array();
        if($allBrand){
            foreach($allBrand as $a){
                $data[]=array(
                    "id"=> $a->id,
                    "name"=> $a->name,
                    "logo"=> url('/').'/storage/images/brand/'.$a->logo,
                    "product"=> $a->products->count(),
                    "companyId"=> $a->companyId,
                    "status"=> $a->status,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                    );
            }
        }
        
        return response()->json(array("allBrand" => $data), 200);
    }

    public function store($token,Request $request){
        $data = User::select('id','companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $rules = ['name' => 'required', 'status' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        
        $brand = new Brand;
        if($request->has('logo')) $brand->logo = $this->uploadImage($request->file('logo'), 'brand');
        $brand->name = $request->name;
        $brand->status = $request->status;
        $brand->companyId = $data['companyId'];
        $brand->userId = $data['id'];

        if(!!$brand->save())
            return response()->json(array("success" => 'Brand created'), 200);
    }

    public function editForm($id){
        $a = Brand::find($id);
        $data=array(
                    "id"=> $a->id,
                    "name"=> $a->name,
                    "logo"=> url('/').'/storage/images/brand/'.$a->logo,
                    "status"=> $a->status,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                    );
        return response()->json(array("data" => $data), 200);
    }

    public function update(Request $request,$token,$id){
        
        $data = User::select('companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $rules = ['name' => 'required', 'status' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $brand = Brand::find($id);
        if($brand->companyId == $data->companyId){

            if($request->has('logo')) 
                if($this->deleteImage($brand->logo, 'brand'))
                    $brand->logo = $this->uploadImage($request->file('logo'), 'brand');
                else
                    $brand->logo = $this->uploadImage($request->file('logo'), 'brand');
    
            $brand->name = $request->name;
            $brand->status = $request->status;
    
             if(!!$brand->save())
                return response()->json(array("success" => 'Brand updated'), 200);
        }else{
            return response()->json(array('errors' => [0 => 'You are not permitted to change this brand!']), 400);
        }

    }
    
    public function delete($token,$id){
        $data = User::select('companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $brand = Brand::find($id);
        if($brand->companyId == $data->companyId){
            if($brand->products->count() <= 0){
                if(!!$brand->delete()){
                    $this->deleteImage($brand->icon, 'brand');
                    return response()->json(array("success" => 'brand deleted'), 200);
                }
            }else{
                return response()->json(array('errors' => [0 => 'This brand has product!']), 400);
            }
        }else{
            return response()->json(array('errors' => [0 => 'You are not permitted to delete this brand!']), 400);
        }
        
    }

}
