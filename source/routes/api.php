<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'Api\AuthController@login');
Route::post('/register', 'Api\AuthController@register');

Route::post('/forget', 'Api\AuthController@forgotPassword');
Route::post('/reset', 'Api\AuthController@resetPassword');

Route::get('/profiledata/{token}', 'Api\UserController@userProfile');
Route::post('/changeprofile/{token}', 'Api\UserController@changeprofile');
Route::post('/changepassword/{token}', 'Api\UserController@changePass');
/* user api */
Route::get('/userdata/{token}/{count?}', 'Api\UserController@index');
Route::get('/rolelist/{token}', 'Api\UserController@rolelist');
Route::post('/user/add/{token}', 'Api\UserController@store');
Route::get('/user/edit/{token}/{id}', 'Api\UserController@editForm');
Route::post('/user/update/{id}', 'Api\UserController@update');

/*product api */
Route::get('/product/all/{token}/{count?}', 'Api\ProductController@index');
Route::post('/product/add/{token}', 'Api\ProductController@store');
Route::get('/product/edit/{token}/{id}', 'Api\ProductController@editForm');
Route::post('/product/update/{id}', 'Api\ProductController@update');

Route::get('/product/import/{token}/{count?}', 'Api\ProductController@importProductList');
Route::get('/product/import/{sl}', 'Api\ProductController@getProduct');
Route::post('/product/import/save/{token}', 'Api\ProductController@importProduct');

/*product api */
Route::get('/purchase/all/{token}/{count?}', 'Api\PurchaseController@index');
Route::get('/purchase/replace/{token}/{count?}', 'Api\PurchaseController@replaceAll');
Route::get('/purchase/return/{token}/{count?}', 'Api\PurchaseController@returnAll');

Route::post('/purchase/add/{token}', 'Api\PurchaseController@store');
Route::get('/purchase/edit/{token}/{id}', 'Api\PurchaseController@editForm');
Route::post('/purchase/update/{id}', 'Api\PurchaseController@update');

/*Bill api */
Route::get('/bill/all/{token}/{count?}', 'Api\BillController@index');
Route::get('/bill/replace/{token}/{count?}', 'Api\BillController@replaceAll');
Route::get('/bill/return/{token}/{count?}', 'Api\BillController@returnAll');

Route::get('/bill/batch_stock/{token}/{product_id}', 'Api\BillController@getBatch');

Route::post('/bill/add/{token}', 'Api\BillController@store');
Route::get('/bill/edit/{token}/{id}', 'Api\BillController@editForm');
Route::post('/bill/update/{id}', 'Api\BillController@update');

/* setting api */
    Route::get('/setting/district', 'Api\SettingController@getdistrict');
    Route::get('/setting/city/{dist?}', 'Api\SettingController@getcity');
    /*Branch Api*/
    Route::get('/branch/all/{token}/{count?}', 'Api\BranchController@index');
    Route::post('/branch/add/{token}', 'Api\BranchController@store');
    Route::get('/branch/edit/{token}/{id}', 'Api\BranchController@editForm');
    Route::post('/branch/update/{id}', 'Api\BranchController@update');
    /*Brand Api*/
    Route::get('/brand/all/{token}/{count?}', 'Api\BrandController@index');
    Route::post('/brand/add/{token}', 'Api\BrandController@store');
    Route::get('/brand/edit/{id}', 'Api\BrandController@editForm');
    Route::post('/brand/update/{token}/{id}', 'Api\BrandController@update');
    Route::get('/brand/delete/{token}/{id}', 'Api\BrandController@delete');
    /*Category Api*/
    Route::get('/category/all/{token}/{count?}', 'Api\CategoryController@index');
    Route::post('/category/add/{token}', 'Api\CategoryController@store');
    Route::get('/category/edit/{id}', 'Api\CategoryController@editForm');
    Route::post('/category/update/{token}/{id}', 'Api\CategoryController@update');
    Route::get('/category/delete/{token}/{id}', 'Api\CategoryController@delete');
    /*Company Api*/
    Route::get('/company/{token}', 'Api\CompanyController@index');
    Route::post('/company/update/{token}/{id}', 'Api\CompanyController@update');


/*Customer Api*/
Route::get('/customer/all/{token}/{count?}', 'Api\CustomerController@index');
Route::post('/customer/add/{token}', 'Api\CustomerController@store');
Route::get('/customer/edit/{token}/{id}', 'Api\CustomerController@editForm');
Route::post('/customer/update/{token}', 'Api\CustomerController@update');

/*Supplier Api*/
Route::get('/supplier/all/{token}/{count?}', 'Api\SupplierController@index');
Route::post('/supplier/add/{token}', 'Api\SupplierController@store');
Route::get('/supplier/edit/{token}/{id}', 'Api\SupplierController@editForm');
Route::post('/supplier/update/{id}', 'Api\SupplierController@update');

/*Purchase Api*/
Route::get('/allPurchase/{token}/{count?}', 'Api\PurchaseController@allPurchase');
Route::get('/replaceAll/{token}/{count?}', 'Api\PurchaseController@replaceAll');
Route::get('/returnAll/{token}/{count?}', 'Api\PurchaseController@returnAll');
Route::get('/purchase/add/{token}', 'Api\PurchaseController@addForm');

/*Warrenty Api */
Route::get('/warrenty/all/{token}/{count?}', 'Api\WarrentyController@index');
Route::get('/warrenty/productDetails', 'Api\WarrentyController@productDetails');
Route::get('/warrenty/customerDetails', 'Api\WarrentyController@customerDetails');
Route::get('/warrenty/add/{token}', 'Api\WarrentyController@addForm');
Route::post('/warrenty/add/{token}', 'Api\WarrentyController@store');
Route::get('/warrenty/show/{token}/{id}', 'Api\WarrentyController@show');
Route::post('/warrenty/update/{token}/{id}', 'Api\WarrentyController@update');

/*Service Api*/
Route::get('/service/all/{token}/{count?}', 'Api\ServiceController@index');
Route::get('/service/productDetails', 'Api\ServiceController@productDetails');
Route::get('/service/add/{token}', 'Api\ServiceController@addForm');
Route::post('/service/add/{token}', 'Api\ServiceController@store');
Route::get('/service/edit/{token}/{id}', 'Api\ServiceController@show');
Route::post('/service/update/{token}/{id}', 'Api\ServiceController@update');


Route::get('/getProductDetails/{token}/{product_id?}', 'Api\PurchaseController@getProductDetails');

Route::post('/purchase/add/{token}', 'Api\PurchaseController@store');

/*Forgot Password*/
Route::post('/password/username', 'Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/verify-code/{token}', 'Api\ForgotPasswordController@verifyCode');