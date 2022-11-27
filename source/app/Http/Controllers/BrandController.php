<?php

namespace App\Http\Controllers;
use App\Http\Requests\Brand\NewBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Brand;
use Exception;
use Carbon\Carbon;

class BrandController extends Controller
{
    use ResponseTrait, ImageHandleTraits;

    public function index(){
        if(company())
            $allBrand = Brand::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->paginate(25);
        else
            $allBrand = Brand::whereNull('companyId')->orderBy('id', 'DESC')->paginate(25);
            
        return view('brand.index', compact('allBrand'));
    }

    public function addForm(){
        return view('brand.add_new');
    }

    public function store(NewBrandRequest $request){
        try {
            $brand = new Brand;
            if($request->has('brandLogo')) $brand->logo = $this->uploadImage($request->file('brandLogo'), 'brand');
            $brand->name = $request->brandName;
            $brand->status = $request->status;
            $brand->userId = encryptor('decrypt', $request->userId);
            if(company())
                $brand->companyId= company()['companyId'];
            $brand->created_at = Carbon::now();

            if(!!$brand->save()) return redirect(route(currentUser().'.allBrand'))->with($this->responseMessage(true, null, 'Brand created'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allBrand'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function editForm($id){
        $brand = Brand::find(encryptor('decrypt', $id));
        return view('brand.edit', compact(['brand']));
    }

    public function update(UpdateBrandRequest $request){
        try {
            $brand = Brand::find(encryptor('decrypt', $request->id));

            if($request->has('brandLogo')) 
                if($this->deleteImage($brand->logo, 'brand'))
                    $brand->logo = $this->uploadImage($request->file('brandLogo'), 'brand');
                else
                    $brand->logo = $this->uploadImage($request->file('brandLogo'), 'brand');

            $brand->name = $request->brandName;
            $brand->status = $request->status;
            $brand->userId = encryptor('decrypt', $request->userId);
            $brand->updated_at = Carbon::now();

            if(!!$brand->save()) return redirect(route(currentUser().'.allBrand'))->with($this->responseMessage(true, null, 'Brand updated'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allBrand'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function delete($id){
        try {
            $brand = Brand::find(encryptor('decrypt', $id));
            if(!!$brand->delete()){
                $this->deleteImage($brand->logo, 'brand');
                return redirect(route(currentUser().'.allBrand'))->with($this->responseMessage(true, null, 'Brand deleted'));
            }
        }catch (Exception $e) {
            return redirect(route(currentUser().'.allBrand'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
}
