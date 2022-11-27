<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\Product\NewProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;

use App\Models\Stock;
use App\Models\Product;
use App\Models\ProductList;
use App\Models\Category;
use App\Models\Company;
use App\Models\Brand;
use App\Models\BillItem;

use Exception;
use Carbon\Carbon;
use DB;
use DNS1D;

class ProductController extends Controller
{
    use ResponseTrait, ImageHandleTraits;

    public function index(){
		if(company()['companyId']){
			$where['companyId']=company()['companyId'];
			$company_id=company()['companyId'];
		}
		//$sellCount=BillItem::where(['companyId'=>$company_id])->groupBy('item_id')->select('item_id', DB::raw('sum(qty) as total'))->get();
		$allProduct = Product::with(['brand'])
			->where($where)
			->orderBy('id', 'DESC')
			->paginate(25);
        return view('product.index', compact('allProduct'));
    }

    public function addForm(){
        $allCategory = Category::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->get();
        $allBrand = Brand::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->get();
        return view('product.add_new', compact([
            'allBrand',
			'allCategory'
        ]));
    }

    public function store(NewProductRequest $request){
        try {
            $pro = new Product;

            if($request->has('thumbnail')) $pro->thumbnail = $this->uploadImage($request->file('thumbnail'), 'product/thumbnail');

            $pro->brandId = $request->brand;
            $pro->categoryId = $request->category;
            $pro->name = $request->name;
            
            $pro->sku = company()['companyId'].'-'.str_pad(Product::where(company())->where(branch())->latest()->count() + 1,5,"0",STR_PAD_LEFT);
            
            $pro->shortDescription = $request->shortDescription;
            $pro->description = $request->description;
            $pro->modelName = $request->modelName;
            $pro->modelNo = $request->modelNo;
            $pro->warrenty = $request->warrenty;
            $pro->status = 1;
            $pro->userId = encryptor('decrypt', $request->userId);
            $pro->branchId = branch()['branchId'];
            $pro->companyId = company()['companyId'];

            if(!!$pro->save()){
                return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(true, null, 'Product created'));
            }

        } catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function show($id){
        $data = Product::where('id',encryptor('decrypt', $id))->first();
        return view('product.show', compact('data'));
    }
    
    public function editForm($id){
        $data = Product::where('id',encryptor('decrypt', $id))->first();
		$allCategory = Category::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->get();
        $allBrand = Brand::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->get();
        return view('product.edit', compact([
            'data','allCategory','allBrand'
        ]));
    }

    public function update(Request $request){
        try {
            $pro=Product::find(encryptor('decrypt', $request->id));

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

            if(!!$pro->save()){
                return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(true, null, 'Product updated'));
            }

        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
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
            return redirect(route(currentUser().'.allProduct'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
	
    public function importProductList(Request $request){
        /* remove all search element */
		if(isset($_GET['fresh']) && $_GET['fresh']){
		    $request->session()->forget('brands');
		    $request->session()->forget('category');
		    $request->session()->forget('name');
		}
			
		$where['status']=1;
		if(isset($_GET['brands']) && $_GET['brands'])
			$request->session()->put('brands', $_GET['brands']);
		
		if(isset($_GET['category']) && $_GET['category'])
		    $request->session()->put('category', $_GET['category']);
		
		if(isset($_GET['name']) && $_GET['name'])
		    $request->session()->put('name', $_GET['name']);
		
		
		if($request->session()->has('brands') && $request->session()->get('brands'))
			$where['brandId']=$request->session()->get('brands');
		if($request->session()->has('category') && $request->session()->get('category'))
			$where['categoryId']=$request->session()->get('category');
			
		//$pro = Product::select('productId')->where(company())->where(branch())->pluck('productId')->toArray();

        $allCategory = Category::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->get();
        $allBrand = Brand::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->get();
        //$productlist=ProductList::where($where)->whereNotIn('id',$pro);
        $productlist=ProductList::where($where);
		
		if($request->session()->has('name') && $request->session()->get('name')){
		    $name=$request->session()->get('name');
			$productlist = $productlist->where('name','LIKE', "{$name}%");
		}
		$productlist = $productlist->orderBy('name', 'ASC')->paginate(25);
			
        return view('product.productlist', compact('productlist','allCategory','allBrand'));
    }

    public function importProduct(){
        try {
            $pro = ProductList::findOrFail($_GET['pid']);
            $p = new Product;
            $p->thumbnail = $pro->thumbnail;
            $p->brandId = $pro->brandId;
            $p->categoryId = $pro->categoryId;
            $p->name = $pro->name;
            $p->sku = company()['companyId'].'-'.str_pad(Product::where(company())->where(branch())->latest()->count() + 1,5,"0",STR_PAD_LEFT);
            $p->shortDescription = $pro->shortDescription;
            $p->description = $pro->description;
            $p->modelName = $pro->modelName;
            $p->modelNo = $pro->modelNo;
            $p->status = 1;
            $p->userId = currentUserId();
            $p->branchId = branch()['branchId'];
            $p->companyId = company()['companyId'];
            $p->save();
			return false;
        } catch (Exception $e) {
            dd($e);
        }

    }

    public function productForBarcode(Request $request){
        /* remove all search element */
		if(isset($_GET['fresh']) && $_GET['fresh']){
		    $request->session()->forget('name');
		}
		$where['products.status']=1;
		if(company()['companyId']){
			$where['products.companyId']=company()['companyId'];
			$company_id=company()['companyId'];
		}
		
		if(isset($_GET['name']) && $_GET['name'])
		    $request->session()->put('name', $_GET['name']);
		
        $productlist=Product::where($where);
		
		if($request->session()->has('name') && $request->session()->get('name')){
		    $name=$request->session()->get('name');
			$productlist = $productlist->where('products.name','LIKE', "{$name}%");
		}
        $productlist = $productlist->rightJoin('stocks', 'products.id', '=', 'stocks.productId')
        ->select('products.id','products.name','stocks.sellPrice','stocks.batchId','stocks.stock');
		
        $productlist = $productlist->orderBy('products.name', 'ASC')->paginate(25);
			
        return view('product.productlistbarcode', compact('productlist'));
    }
    public function barcodePrintPreview(Request $request){
        
        $productlist=DB::select("SELECT p.id,p.name,stocks.sellPrice,stocks.batchId,stocks.stock FROM `products` as p JOIN stocks on stocks.productId=p.id WHERE p.id in (".implode(',',$request->datas).")");//Product::whereIn('id',$request->datas)->get();
        $barcode="<div class='row'>";
		if($productlist){
            foreach($productlist as $ps){
                $barcode.="<div class='col-6 text-center px-1 py-1'>";
                $barcode.="<div class='fw-bold text-dark'>$ps->name</div>";
                $barcode.="<div class='fw-bold fs-4'>$ps->sellPrice</div>";
                $barcode.="<div class='text-center'>".DNS1D::getBarcodeHTML("$ps->id-$ps->batchId", 'C128',1,25)."</div>";
                $barcode.="<div class='text-center'>$ps->id-$ps->batchId</div>";
                $barcode.="</div>";
            }
        }
		$barcode.="</div>";
        echo json_encode($barcode);
    }

    public function barcodePrint(Request $request){
        
        $productlist=DB::select("SELECT p.id,p.name,stocks.sellPrice,stocks.batchId,stocks.stock FROM `products` as p JOIN stocks on stocks.productId=p.id WHERE p.id in (".implode(',',$request->datas).")");//Product::whereIn('id',$request->datas)->get();
        $barcode="<div class='row'>";
		if($productlist){
            foreach($productlist as $ps){
                $barcode.="<div class='col-6 text-center px-1 py-1'>";
                $barcode.="<div class='fw-bold text-dark'>$ps->name</div>";
                $barcode.="<div class='fw-bold fs-4'>$ps->sellPrice</div>";
                $barcode.="<div class='text-center'>".DNS1D::getBarcodeHTML("$ps->id-$ps->batchId", 'C128',1,25)."</div>";
                $barcode.="<div class='text-center'>$ps->id-$ps->batchId</div>";
                $barcode.="</div>";
            }
        }
		$barcode.="</div>";
        echo json_encode($barcode);
    }
	
}
