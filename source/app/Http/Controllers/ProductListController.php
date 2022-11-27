<?php

namespace App\Http\Controllers;

use App\Models\ProductList;
use App\Http\Requests\ProductList\NewProductRequest;
use App\Http\Requests\ProductList\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Stock;

use Exception;
use Carbon\Carbon;

use DB;

class ProductListController extends Controller
{
	use ResponseTrait, ImageHandleTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $allProduct = ProductList::orderBy('id', 'DESC')->paginate(25);
        return view('productList.index', compact('allProduct'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addForm(){
        $allCategory = Category::orderBy('id', 'DESC')->get();
        $allBrand = Brand::orderBy('id', 'DESC')->get();
        return view('productList.add_new', compact([
            'allBrand',
			'allCategory'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewProductRequest $request){

        try {
            $pro = new ProductList;

            if($request->has('thumbnail')) $pro->thumbnail = $this->uploadImage($request->file('thumbnail'), 'product/thumbnail');

            $pro->brandId = $request->brand;
            $pro->categoryId = $request->category;
            $pro->name = $request->name;
            $pro->serialNo = $request->serialNo;
            $pro->shortDescription = $request->shortDescription;
            $pro->description = $request->description;
            $pro->modelName = $request->modelName;
            $pro->modelNo = $request->modelNo;
            $pro->warrenty = $request->warrenty;
            $pro->status = $request->status;
            $pro->userId = encryptor('decrypt', $request->userId);

            if(!!$pro->save()){
                return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(true, null, 'Product created'));
            }

        } catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MedicineList  $medicineList
     * @return \Illuminate\Http\Response
     */
    public function editForm($id){
        $data = ProductList::find(encryptor('decrypt', $id));

        $allCategory = Category::orderBy('id', 'DESC')->get();
        $allBrand = Brand::orderBy('id', 'DESC')->get();
		
        return view('productList.edit', compact([
            'data',
            'allCategory',
            'allBrand',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MedicineList  $medicineList
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request){
 
        try {
            $product = ProductList::find(encryptor('decrypt', $request->id));

            if($request->has('thumbnail')) 
                if($this->deleteImage($product->thumbnail, 'product/thumbnail'))
                    $product->thumbnail = $this->uploadImage($request->file('thumbnail'), 'product/thumbnail');
                else
                    $product->thumbnail = $this->uploadImage($request->file('thumbnail'), 'product/thumbnail');

            $product->brandId = $request->brand;
            $product->categoryId = $request->category;
            $product->name = $request->name;
            $product->serialNo = $request->serialNo;
            $product->shortDescription = $request->shortDescription;
            $product->description = $request->description;
            $product->modelName = $request->modelName;
            $product->modelNo = $request->modelNo;
            $product->warrenty = $request->warrenty;
            $product->status = $request->status;
            $product->userId = encryptor('decrypt', $request->userId);

            if(!!$product->save()) {
                return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(true, null, 'Product updated'));
            }
        } catch (Exception $e) {
            //dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
