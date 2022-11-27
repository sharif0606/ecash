<?php

namespace App\Http\Controllers;
use App\Http\Requests\Category\NewCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Category;
use Exception;
use Carbon\Carbon;

class CategoryController extends Controller
{
    use ResponseTrait, ImageHandleTraits;
    
    public function index(){
        if(company())
            $allCategory = Category::where(company())->orWhereNull('companyId')->orderBy('id', 'DESC')->paginate(25);
        else
            $allCategory = Category::whereNull('companyId')->orderBy('id', 'DESC')->paginate(25);
        return view('category.index', compact('allCategory'));
    }

    public function addForm(){
        return view('category.add_new');
    }

    public function store(newCategoryRequest $request){
        try {
            $category = new Category;
            if($request->has('categoryIcon')) $category->icon = $this->uploadImage($request->file('categoryIcon'), 'category');
            $category->name = $request->categoryName;
            $category->status = $request->status;
            $category->userId = encryptor('decrypt', $request->userId);
            if(company())
                $category->companyId= company()['companyId'];
                
            $category->created_at = Carbon::now();

            if(!!$category->save()) return redirect(route(currentUser().'.allCategory'))->with($this->responseMessage(true, null, 'Category created'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allCategory'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function editForm($id){
        $category = Category::find(encryptor('decrypt', $id));
        return view('category.edit', compact(['category']));
    }

    public function update(updateCategoryRequest $request){
        try {
            $category = Category::find(encryptor('decrypt', $request->id));

            if($request->has('categoryIcon')) 
                if($this->deleteImage($category->icon, 'category'))
                    $category->icon = $this->uploadImage($request->file('categoryIcon'), 'category');
                else
                    $category->icon = $this->uploadImage($request->file('categoryIcon'), 'category');

            $category->name = $request->categoryName;
            $category->status = $request->status;
            $category->userId = encryptor('decrypt', $request->userId);
            $category->updated_at = Carbon::now();

            if(!!$category->save()) return redirect(route(currentUser().'.allCategory'))->with($this->responseMessage(true, null, 'Category updated'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allCategory'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function delete($id){
        try {
            $category = Category::find(encryptor('decrypt', $id));
            if(!!$category->delete()){
                $this->deleteImage($category->icon, 'category');
                return redirect(route(currentUser().'.allCategory'))->with($this->responseMessage(true, null, 'Category deleted'));
            }
        }catch (Exception $e) {
            return redirect(route(currentUser().'.allCategory'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

}
