<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
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
        
        $allCategory = Category::where('companyId', $companyId)->orWhereNull('companyId')->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
        $data=array();
        if($allCategory){
            foreach($allCategory as $a){
                $data[]=array(
                    "id"=> $a->id,
                    "name"=> $a->name,
                    "icon"=> url('/').'/storage/images/category/'.$a->icon,
                    "status"=> $a->status,
                    "product"=> $a->products->count(),
                    "companyId"=> $a->companyId,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                    );
            }
        }
        
        return response()->json(array("allCategory" => $data), 200);
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
        
            $category = new Category;
            if($request->has('icon')) $category->icon = $this->uploadImage($request->file('icon'), 'category');
            $category->name = $request->name;
            $category->status = $request->status;
            $category->userId = $data['id'];
            $category->companyId = $data['companyId'];
            if(!!$category->save())
                return response()->json(array("success" => 'Category created'), 200);
            

    }

    public function editForm($id){
        $data = Category::find($id);
        $data=array(
                    "id"=> $data->id,
                    "name"=> $data->name,
                    "icon"=> url('/').'/storage/images/category/'.$data->icon,
                    "status"=> $data->status,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($data->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($data->updated_at))
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
        $category = Category::find($id);
        if($category->companyId == $data->companyId){
            if($request->has('icon')) 
                if($this->deleteImage($category->icon, 'category'))
                    $category->icon = $this->uploadImage($request->file('icon'), 'category');
                else
                    $category->icon = $this->uploadImage($request->file('icon'), 'category');
    
            $category->name = $request->name;
            $category->status = $request->status;
            if(!!$category->save())
                return response()->json(array("success" => 'Category updated'), 200);
        }else{
            return response()->json(array('errors' => [0 => 'You are not permitted to change this category!']), 400);
        }

    }

    public function delete($token,$id){
        $data = User::select('companyId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $category = Category::find($id);
        if($category->companyId == $data->companyId){
            if($category->products->count() <= 0){
                if(!!$category->delete()){
                    $this->deleteImage($category->icon, 'category');
                    return response()->json(array("success" => 'Category deleted'), 200);
                }
            }else{
                return response()->json(array('errors' => [0 => 'This category has product!']), 400);
            }
        }else{
            return response()->json(array('errors' => [0 => 'You are not permitted to delete this category!']), 400);
        }
        
    }

}
