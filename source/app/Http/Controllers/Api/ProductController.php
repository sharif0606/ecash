<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageHandleTraits;

use App\Models\Stock;
use App\Models\Product;
use App\Models\ProductList;
use App\Models\Category;
use App\Models\Company;
use App\Models\Brand;
use App\Models\User;

use Exception;
use Carbon\Carbon;

use DB;

class ProductController extends Controller
{
    use ImageHandleTraits;
    
    public function index($token, $limit = 10, Request $request){
        $data = User::select('companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        if($data['companyId']){
			$where['companyId']=$data['companyId'];
		}
        if($data['branchId']){
			$where['branchId']=$data['branchId'];
		}
		
        $page = $request->has('page') ? $request->get('page') : 1;
        $allProduct = Product::where($where);
        if($request->has('name')){
            $n=$request->get('name');
            $allProduct = $allProduct->where('name', 'LIKE', "%$n%");
        }
        
		$allProduct = $allProduct->orderBy('id', 'DESC')->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
        $data=array();
        if($allProduct){
            foreach($allProduct as $a){
                $data[]=array(
                    "id"=> $a->id,
                    "thumbnail"=> "https://cashbaksho.com/storage/images/product/thumbnail/".$a->thumbnail,
                    "sku"=> $a->sku,
                    "brandId"=> $a->brandId,
                    "brand"=> $a->brand->name,
                    "categoryId"=> $a->categoryId,
                    "category"=> $a->categories->name,
                    "name"=> $a->name,
                    "shortDescription"=> $a->shortDescription,
                    "description"=> $a->description,
                    "modelName"=> $a->modelName,
                    "modelNo"=> $a->modelNo,
                    "warrenty"=> $a->warrenty,
                    "companyId"=> $a->companyId,
                    "branchId"=> $a->branchId,
                    "userId"=> $a->userId,
                    "status"=> $a->status,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                );
            }
        }
        return response()->json(array("allProduct" => $data), 200);
    }

    public function store($token, Request $request){
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $rules = ['brandId' => 'required', 'categoryId' => 'required', 'name' => 'required', 'status' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
        $pro = new Product;

        if($request->has('thumbnail')) $pro->thumbnail = $this->uploadImage($request->file('thumbnail'), 'product/thumbnail');

        $pro->brandId = $request->brandId;
        $pro->categoryId = $request->categoryId;
        $pro->name = $request->name;
        $pro->sku = $data->companyId.'-'.str_pad(Product::where('companyId',$data->companyId)->where('branchId',$data->branchId)->latest()->count() + 1,5,"0",STR_PAD_LEFT);
        $pro->shortDescription = $request->shortDescription;
        $pro->description = $request->description;
        $pro->modelName = $request->modelName;
        $pro->modelNo = $request->modelNo;
        $pro->warrenty = $request->warrenty;
        $pro->status = $request->status;
        $pro->userId = $data->id;
        $pro->branchId = $data->branchId;
        $pro->companyId = $data->companyId;

        if (!!$pro->save())
            return response()->json(array("success" => 'Product created','data'=>$pro), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);
    }

    public function editForm($token, $id){
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        $a = Product::where('id',$id)->first();
        if($a){
    		 $data=array(
                    "id"=> $a->id,
                    "thumbnail"=> "https://cashbaksho.com/storage/images/product/thumbnail/".$a->thumbnail,
                    "sku"=> $a->sku,
                    "brandId"=> $a->brandId,
                    "brand"=> $a->brand?$a->brand->name:'',
                    "categoryId"=> $a->categoryId,
                    "category"=> $a->categories?$a->categories->name:"",
                    "name"=> $a->name,
                    "shortDescription"=> $a->shortDescription,
                    "description"=> $a->description,
                    "modelName"=> $a->modelName,
                    "modelNo"=> $a->modelNo,
                    "warrenty"=> $a->warrenty,
                    "companyId"=> $a->companyId,
                    "branchId"=> $a->branchId,
                    "userId"=> $a->userId,
                    "status"=> $a->status,
                    "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                    "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                );
                $rp = Product::where('brandId',$a->brandId)->get();
                $datarp=array();
                if($rp){
                    foreach($rp as $a){
                        $datarp[]=array(
                            "id"=> $a->id,
                            "thumbnail"=> "https://cashbaksho.com/storage/images/product/thumbnail/".$a->thumbnail,
                            "sku"=> $a->sku,
                            "brandId"=> $a->brandId,
                            "brand"=> $a->brand?$a->brand->name:'',
                            "categoryId"=> $a->categoryId,
                            "category"=> $a->categories?$a->categories->name:"",
                            "name"=> $a->name,
                            "shortDescription"=> $a->shortDescription,
                            "description"=> $a->description,
                            "modelName"=> $a->modelName,
                            "modelNo"=> $a->modelNo,
                            "warrenty"=> $a->warrenty,
                            "companyId"=> $a->companyId,
                            "branchId"=> $a->branchId,
                            "userId"=> $a->userId,
                            "status"=> $a->status,
                            "created_at"=> date('Y-m-d H:i:s',strtotime($a->created_at)),
                            "updated_at"=> date('Y-m-d H:i:s',strtotime($a->updated_at))
                        );
                    }
                }
                
                $status=Stock::where('productId',$id)->get();
                
            return response()->json(array("product" => $data,"rp"=>$datarp,"details"=>$status), 200);
        }else{
            return response()->json(array('errors' => [0 => 'Product not found!']), 400);
        }
        
    }

    public function update(Request $request,$id){
        
        $rules = ['brandId' => 'required', 'categoryId' => 'required', 'name' => 'required',  'status' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }
            $pro=Product::find($id);

            if($request->has('thumbnail')) 
                if($this->deleteImage($pro->thumbnail, 'product/thumbnail'))
                    $pro->thumbnail = $this->uploadImage($request->file('thumbnail'), 'product/thumbnail');
                else
                    $pro->thumbnail = $this->uploadImage($request->file('thumbnail'), 'product/thumbnail');

            $pro->brandId = $request->brand;
            $pro->categoryId = $request->category;
            $pro->name = $request->name;
            $pro->shortDescription = $request->shortDescription;
            $pro->description = $request->description;
            $pro->modelName = $request->modelName;
            $pro->modelNo = $request->modelNo;
            $pro->warrenty = $request->warrenty;
            $pro->status = $request->status;

        if (!!$pro->save())
            return response()->json(array("success" => 'Product updated','data'=>$pro), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);

        
    }

    public function delete($id){
        try {
            $product = Product::find(encryptor('decrypt', $id));
            if($product != null && $product->stocks->count()==0){
                $this->deleteImage($product->thumbnail, 'product/thumbnail');
                $product->delete();
                return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(true, null, 'Product deleted'));
            }else{
                return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(false, 'error', 'This product has stock so you cannot delete!'));
            }
        }catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
	
    public function importProductList($token, $limit = 10, Request $request){
        	
		$where['status']=1;
		if(isset($request->brand) && $request->brand)
		    $where['brandId']=$request->brand;
		
		if(isset($request->category) && $request->category)
		    $where['categoryId']=$request->category;
		    
        $productlist=ProductList::with('brand','categories')->where($where);
		
		if(isset($request->name) && $request->name){
		    $name=$request->name;
			$productlist = $productlist->where('name','LIKE', "{$name}%");
		}
		$page = $request->has('page') ? $request->get('page') : 1;
		
		$productlist = $productlist->latest()->limit($limit)->offset(($page - 1) * $limit)->get();
		
		if($productlist)
		    return response()->json(array("productlist" => $productlist), 200);
		else
            return response()->json(array('errors' => "No Product Found"), 400);
    }

    public function getProduct($slno){
		
		$productlist = ProductList::where("serialNo",$request->slno)->first();
		if($productlist)
		    return response()->json(array("productlist" => $productlist), 200);
		else
            return response()->json(array('errors' => "No Product Found"), 400);
		
    }

    public function importProduct($token, Request $request){
        $data = User::select('id', 'companyId', 'branchId')->where('api_token', $token)->first();
        if (!$data) {
            return response()->json(array('errors' => [0 => 'Token is not valid!']), 400);
            exit;
        }
        
        $rules = ['pid' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) 
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        
        $pro = ProductList::findOrFail($request->pid);
        $p = new Product;
        $p->thumbnail           = $pro->thumbnail;
        $p->brandId             = $pro->brandId;
        $p->categoryId          = $pro->categoryId;
        $p->name                = $pro->name;
        $p->sku                 = $data->companyId.'-'.str_pad(Product::where('companyId',$data->companyId)->where('branchId',$data->branchId)->latest()->count() + 1,5,"0",STR_PAD_LEFT);
        $p->shortDescription    = $pro->shortDescription;
        $p->description         = $pro->description;
        $p->modelName           = $pro->modelName;
        $p->modelNo             = $pro->modelNo;
        $p->warrenty            = $pro->warrenty;
        $p->status              = $pro->status;
        $p->userId              = $data->id;
        $p->branchId            = $data->branchId;
        $p->companyId           = $data->companyId;
        if (!!$p->save())
            return response()->json(array("success" => 'Product created','data'=>$p), 200);
        else
            return response()->json(array('errors' => "Please try again."), 400);
    }
	
}
