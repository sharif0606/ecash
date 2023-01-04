<?php

use Illuminate\Support\Facades\Route;

//Route::get('/mail-send', "MailController@send")->name('mailSend');

Route::group(['middleware' => 'unknownUser'], function(){
    // Sign In, Login
    Route::get('/', "AuthenticationController@signInForm")->name('signInForm');
    Route::get('/sign-in', "AuthenticationController@signInForm")->name('signInForm');
    Route::get('/login', "AuthenticationController@signInForm")->name('signInForm');

    Route::post('/sign-in', "AuthenticationController@signIn")->name('logIn');
    Route::post('/login', "AuthenticationController@signIn")->name('logIn');

    // Sign Up, Registration
    Route::get('/sign-up', "AuthenticationController@signUpForm")->name('signUpForm');
    Route::get('/register', "AuthenticationController@signUpForm")->name('signUpForm');
    Route::get('/registration', "AuthenticationController@signUpForm")->name('signUpForm');
	Route::post('/registered', 'AuthenticationController@signUpStore')->name('signUp');


    // Forgot Password
    Route::get('/forgot', "AuthenticationController@forgotForm")->name('forgotPasswordForm');
    Route::get('/forgot-pass', "AuthenticationController@forgotForm")->name('forgotPasswordForm');
    Route::get('/forgot-password', "AuthenticationController@forgotForm")->name('forgotPasswordForm');

    Route::post('/forgot-password', "AuthenticationController@forgotPassword")->name('forgotPassword');

    // Reset Password
    Route::get('/reset-password', "AuthenticationController@resetPasswordForm")->name('resetPasswordForm');
    Route::post('/reset-password', "AuthenticationController@resetPassword")->name('resetPassword');
    
	//OTP
	Route::get('/authorization/{id?}', 'AuthorizationController@authorizeForm')->name('authorization');
	Route::get('resend-verify/{id}', 'AuthorizationController@sendVerifyCode')->name('send_verify_code');
	//Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify_email');
	Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify_sms');
	//Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');
});

Route::get('/sign-out', "AuthenticationController@signOut")->name('logOut');

Route::get('/logout', "AuthenticationController@signOut")->name('logOut');

/*Customer Wise Bill List By OTP*/
    Route::get('/customer-otp',"CustomerBillController@index");
	Route::post('/customer/authorization/', 'CustomerBillController@authorizeForm')->name('cus-authorization');
	Route::get('/customer/resend-verify/{id}', 'CustomerBillController@sendVerifyCode')->name('cus_send_verify_code');
	Route::post('/customer/verify-sms', 'CustomerBillController@smsVerification')->name('cus_verify_sms');
	Route::get('/customer/bill/{custCode}', 'CustomerBillController@customerBill')->name('bill');
// Super Admin
Route::group(['middleware' => 'isSuperAdmin'], function(){
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('superadminDashboard');

        Route::prefix('user')->group(function () {
            Route::get('/all', 'UserController@index')->name('superadmin.allUser');
            Route::get('/add', 'UserController@addForm')->name('superadmin.addNewUserForm');
            Route::post('/add', 'UserController@store')->name('superadmin.addNewUser');
            Route::get('/edit/{name}/{id}', 'UserController@editForm')->name('superadmin.editUser');
            Route::post('/update', 'UserController@update')->name('superadmin.updateUser');
            Route::get('/delete/{name}/{id}', 'UserController@delete')->name('superadmin.deleteUser');
            
            Route::get('/profile', 'UserController@userProfile')->name('superadmin.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('superadmin.storeProfile');
            Route::post('/changePass', 'UserController@changePass')->name('superadmin.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('superadmin.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('superadmin.changeAcc');
        });
    
        Route::prefix('role')->group(function () {
            Route::get('/all', 'RoleController@index')->name('allRole');
            Route::get('/add', 'RoleController@addRoleForm')->name('addNewRoleForm');
            Route::post('/add', 'RoleController@store')->name('addNewRole');
            Route::get('/edit/{name}/{id}', 'RoleController@editForm');
            Route::post('/update', 'RoleController@update')->name('updateRole');
            Route::get('/delete/{name}/{id}', 'RoleController@delete');
        });

        Route::prefix('coupon')->group(function () {
            Route::get('/all', 'CouponController@index')->name('superadmin.allCoupon');
            Route::get('/add', 'CouponController@addForm')->name('superadmin.addNewCouponForm');
            Route::post('/add', 'CouponController@store')->name('superadmin.addNewCoupon');
            Route::get('/edit/{id}', 'CouponController@editForm')->name('superadmin.editCoupon');
            Route::post('/update', 'CouponController@update')->name('superadmin.updateCoupon');
            Route::get('/delete/{id}', 'CouponController@delete')->name('superadmin.deleteCoupon');
        });

        Route::prefix('package')->group(function () {
            Route::get('/all', 'PackageController@index')->name('superadmin.allPackage');
            Route::get('/add', 'PackageController@addForm')->name('superadmin.addNewPackageForm');
            Route::post('/add', 'PackageController@store')->name('superadmin.addNewPackage');
            Route::get('/edit/{id}', 'PackageController@editForm')->name('superadmin.editPackage');
            Route::post('/update', 'PackageController@update')->name('superadmin.updatePackage');
            Route::get('/delete/{id}', 'PackageController@delete')->name('superadmin.deletePackage');
			
			Route::get('/req_package', 'PackageController@reqPackage')->name('superadmin.reqPackage');
			Route::get('/req_package_pending', 'PackageController@reqPackagepending')->name('superadmin.reqPackagepending');
			Route::get('/active_package', 'PackageController@activePackage')->name('superadmin.activePackage');
            Route::get('/package_status/{status}/{id}', 'PackageController@packageStatus')->name('superadmin.packageStatus');
            Route::get('/cancel_package/{id}', 'PackageController@cancelPackage')->name('superadmin.cancelPackage');
        });

        Route::prefix('userpackage')->group(function () {
            Route::get('/all', 'UserPackageController@index')->name('superadmin.allUserPackage');
            Route::get('/add', 'UserPackageController@addForm')->name('superadmin.addNewUserPackageForm');
            Route::post('/add', 'UserPackageController@store')->name('superadmin.addNewUserPackage');
            Route::get('/edit/{id}', 'UserPackageController@editForm')->name('superadmin.editUserPackage');
            Route::post('/update', 'UserPackageController@update')->name('superadmin.updateUserPackage');
            Route::get('/delete/{id}', 'UserPackageController@delete')->name('superadmin.deleteUserPackage');
            Route::get('/getCoupon', 'UserPackageController@getCoupon')->name('superadmin.getCoupon');
        });

        Route::prefix('state')->group(function () {
            Route::get('/all', 'StateController@index')->name('superadmin.allState');
            Route::get('/add', 'StateController@addForm')->name('superadmin.addNewStateForm');
            Route::post('/add', 'StateController@store')->name('superadmin.addNewState');
            Route::get('/edit/{name}/{id}', 'StateController@editForm')->name('superadmin.editState');
            Route::post('/update', 'StateController@update')->name('superadmin.updateState');
            Route::get('/delete/{name}/{id}', 'StateController@delete')->name('superadmin.deleteState');
        });

        Route::prefix('zone')->group(function () {
            Route::get('/all', 'ZoneController@index')->name('superadmin.allZone');
            Route::get('/add', 'ZoneController@addForm')->name('superadmin.addNewZoneForm');
            Route::post('/add', 'ZoneController@store')->name('superadmin.addNewZone');
            Route::get('/edit/{name}/{id}', 'ZoneController@editForm')->name('superadmin.editZone');
            Route::post('/update', 'ZoneController@update')->name('superadmin.updateZone');
            Route::get('/delete/{name}/{id}', 'ZoneController@delete')->name('superadmin.deleteZone');
        });
    });
});

// Admin
Route::group(['middleware' => 'isAdmin'], function(){
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('adminDashboard');

        Route::prefix('user')->group(function () {
            Route::get('/all', 'UserController@index')->name('admin.allUser');
            Route::get('/add', 'UserController@addForm')->name('admin.addNewUserForm');
            Route::post('/add', 'UserController@store')->name('admin.addNewUser');
            Route::get('/edit/{name}/{id}', 'UserController@editForm')->name('admin.editUser');
            Route::post('/update', 'UserController@update')->name('admin.updateUser');
            Route::get('/delete/{name}/{id}', 'UserController@delete')->name('admin.deleteUser');
            
            Route::get('/profile', 'UserController@userProfile')->name('admin.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('admin.storeProfile');
            Route::post('/changePass', 'UserController@changePass')->name('admin.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('admin.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('admin.changeAcc');
        });

        Route::prefix('userpackage')->group(function () {
            Route::get('/all', 'UserPackageController@index')->name('admin.allUserPackage');
            Route::get('/add', 'UserPackageController@addForm')->name('admin.addNewUserPackageForm');
            Route::post('/add', 'UserPackageController@store')->name('admin.addNewUserPackage');
            Route::get('/edit/{id}', 'UserPackageController@editForm')->name('admin.editUserPackage');
            Route::post('/update', 'UserPackageController@update')->name('admin.updateUserPackage');
            Route::get('/delete/{id}', 'UserPackageController@delete')->name('admin.deleteUserPackage');
            Route::get('/getCoupon', 'UserPackageController@getCoupon')->name('admin.getCoupon');
        });
    });
});

// dataentry
Route::group(['middleware' => 'isDataentry'], function(){
    Route::prefix('dataentry')->group(function () {
        Route::get('/', 'DashboardController@dataentry');
        Route::get('/dashboard', 'DashboardController@dataentry')->name('dataentryDashboard');
		
		Route::prefix('user')->group(function () {
            Route::get('/profile', 'UserController@userProfile')->name('dataentry.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('dataentry.storeProfile');
            
            Route::post('/changePass', 'UserController@changePass')->name('dataentry.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('dataentry.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('dataentry.changeAcc');
        });
		
		
		
        Route::prefix('brand')->group(function () {
            Route::get('/all', 'BrandController@index')->name('dataentry.allBrand');
            Route::get('/add', 'BrandController@addForm')->name('dataentry.addNewBrandForm');
            Route::post('/add', 'BrandController@store')->name('dataentry.addNewBrand');
            Route::get('/edit/{id}', 'BrandController@editForm')->name('dataentry.editBrand');
            Route::post('/update', 'BrandController@update')->name('dataentry.updateBrand');
            Route::get('/delete/{id}', 'BrandController@delete')->name('dataentry.deleteBrand');
        });

        Route::prefix('category')->group(function () {
            Route::get('/all', 'CategoryController@index')->name('dataentry.allCategory');
            Route::get('/add', 'CategoryController@addForm')->name('dataentry.addNewCategoryForm');
            Route::post('/add', 'CategoryController@store')->name('dataentry.addNewCategory');
            Route::get('/edit/{id}', 'CategoryController@editForm')->name('dataentry.editCategory');
            Route::post('/update', 'CategoryController@update')->name('dataentry.updateCategory');
            Route::get('/delete/{id}', 'CategoryController@delete')->name('dataentry.deleteCategory');
        });
		
        Route::prefix('product')->group(function () {
            Route::get('/all', 'ProductListController@index')->name('dataentry.allProduct');
            Route::get('/add', 'ProductListController@addForm')->name('dataentry.addNewProductForm');
            Route::post('/add', 'ProductListController@store')->name('dataentry.addNewProduct');
            Route::get('/edit/{id}', 'ProductListController@editForm')->name('dataentry.editProduct');
            Route::post('/update', 'ProductListController@update')->name('dataentry.updateProduct');
            Route::get('/delete/{id}', 'ProductListController@delete')->name('dataentry.deleteProduct');
        });
		
    });
});

// Executive
Route::group(['middleware' => 'isExecutive'], function(){
    Route::prefix('executive')->group(function () {
        Route::get('/', 'DashboardController@executive');
        Route::get('/dashboard', 'DashboardController@executive')->name('executiveDashboard');
		
		Route::prefix('user')->group(function () {
            Route::get('/all', 'UserController@index')->name('executive.allUser');
            Route::get('/add', 'UserController@addForm')->name('executive.addNewUserForm');
            Route::post('/add', 'UserController@store')->name('executive.addNewUser');
            Route::get('/edit/{name}/{id}', 'UserController@editForm')->name('executive.editUser');
            Route::post('/update', 'UserController@update')->name('executive.updateUser');
            Route::get('/delete/{name}/{id}', 'UserController@delete')->name('executive.deleteUser');
			
			
            Route::get('/owner_list', 'UserController@modList')->name('executive.modList');
            Route::get('/owner_ass/{uid}/{tid}', 'UserController@modAssign')->name('executive.modAssign');
            
            Route::get('/profile', 'UserController@userProfile')->name('executive.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('executive.storeProfile');
            Route::post('/changePass', 'UserController@changePass')->name('executive.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('executive.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('executive.changeAcc');
        });

        Route::prefix('userpackage')->group(function () {
            Route::get('/all', 'UserPackageController@index')->name('executive.allUserPackage');
            Route::get('/add', 'UserPackageController@addForm')->name('executive.addNewUserPackageForm');
            Route::post('/add', 'UserPackageController@store')->name('executive.addNewUserPackage');
            Route::get('/edit/{id}', 'UserPackageController@editForm')->name('executive.editUserPackage');
            Route::post('/update', 'UserPackageController@update')->name('executive.updateUserPackage');
            Route::get('/delete/{id}', 'UserPackageController@delete')->name('executive.deleteUserPackage');
            Route::get('/getCoupon', 'UserPackageController@getCoupon')->name('executive.getCoupon');
        });
		
    });
});

// Account Manager
Route::group(['middleware' => 'isAccountmanager'], function(){
    Route::prefix('accountmanager')->group(function () {
        Route::get('/', 'DashboardController@accountmanager');
        Route::get('/dashboard', 'DashboardController@accountmanager')->name('accountmanagerDashboard');
		
		Route::prefix('user')->group(function () {
            Route::get('/all', 'UserController@index')->name('accountmanager.allUser');
            Route::get('/add', 'UserController@addForm')->name('accountmanager.addNewUserForm');
            Route::post('/add', 'UserController@store')->name('accountmanager.addNewUser');
            Route::get('/edit/{name}/{id}', 'UserController@editForm')->name('accountmanager.editUser');
            Route::post('/update', 'UserController@update')->name('accountmanager.updateUser');
            Route::get('/delete/{name}/{id}', 'UserController@delete')->name('accountmanager.deleteUser');
            
            Route::get('/profile', 'UserController@userProfile')->name('accountmanager.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('accountmanager.storeProfile');
            Route::post('/changePass', 'UserController@changePass')->name('accountmanager.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('accountmanager.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('accountmanager.changeAcc');
        });

        Route::prefix('userpackage')->group(function () {
            Route::get('/all', 'UserPackageController@index')->name('accountmanager.allUserPackage');
            Route::get('/add', 'UserPackageController@addForm')->name('accountmanager.addNewUserPackageForm');
            Route::post('/add', 'UserPackageController@store')->name('accountmanager.addNewUserPackage');
            Route::get('/edit/{id}', 'UserPackageController@editForm')->name('accountmanager.editUserPackage');
            Route::post('/update', 'UserPackageController@update')->name('accountmanager.updateUserPackage');
            Route::get('/delete/{id}', 'UserPackageController@delete')->name('accountmanager.deleteUserPackage');
            Route::get('/getCoupon', 'UserPackageController@getCoupon')->name('accountmanager.getCoupon');
        });
        
		
    });
});

// Marketing Manager
Route::group(['middleware' => 'isMarketingmanager'], function(){
    Route::prefix('marketingmanager')->group(function () {
        Route::get('/', 'DashboardController@marketingmanager');
        Route::get('/dashboard', 'DashboardController@marketingmanager')->name('marketingmanagerDashboard');
		
		Route::prefix('user')->group(function () {
            Route::get('/all', 'UserController@index')->name('marketingmanager.allUser');
            Route::get('/add', 'UserController@addForm')->name('marketingmanager.addNewUserForm');
            Route::post('/add', 'UserController@store')->name('marketingmanager.addNewUser');
            Route::get('/edit/{name}/{id}', 'UserController@editForm')->name('marketingmanager.editUser');
            Route::post('/update', 'UserController@update')->name('marketingmanager.updateUser');
            Route::get('/delete/{name}/{id}', 'UserController@delete')->name('marketingmanager.deleteUser');
            
            Route::get('/profile', 'UserController@userProfile')->name('marketingmanager.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('marketingmanager.storeProfile');
            Route::post('/changePass', 'UserController@changePass')->name('marketingmanager.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('marketingmanager.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('marketingmanager.changeAcc');
        });

        Route::prefix('userpackage')->group(function () {
            Route::get('/all', 'UserPackageController@index')->name('marketingmanager.allUserPackage');
            Route::get('/add', 'UserPackageController@addForm')->name('marketingmanager.addNewUserPackageForm');
            Route::post('/add', 'UserPackageController@store')->name('marketingmanager.addNewUserPackage');
            Route::get('/edit/{id}', 'UserPackageController@editForm')->name('marketingmanager.editUserPackage');
            Route::post('/update', 'UserPackageController@update')->name('marketingmanager.updateUserPackage');
            Route::get('/delete/{id}', 'UserPackageController@delete')->name('marketingmanager.deleteUserPackage');
            Route::get('/getCoupon', 'UserPackageController@getCoupon')->name('marketingmanager.getCoupon');
        });
        
		
    });
});

// owner
Route::group(['middleware' => 'isOwner'], function(){
    Route::prefix('owner')->group(function () {
        Route::get('/', 'DashboardController@owner');
        Route::get('/dashboard', 'DashboardController@owner')->name('ownerDashboard');

        Route::prefix('user')->group(function () {
            Route::get('/all', 'UserController@index')->name('owner.allUser');
            Route::get('/add', 'UserController@addForm')->name('owner.addNewUserForm');
            Route::post('/add', 'UserController@store')->name('owner.addNewUser');
            Route::get('/edit/{name}/{id}', 'UserController@editForm')->name('owner.editUser');
            Route::post('/update', 'UserController@update')->name('owner.updateUser');
            Route::get('/delete/{name}/{id}', 'UserController@delete')->name('owner.deleteUser');
            
            Route::get('/profile', 'UserController@userProfile')->name('owner.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('owner.storeProfile');
            
            Route::post('/changePass', 'UserController@changePass')->name('owner.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('owner.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('owner.changeAcc');
        });
		
        Route::prefix('brand')->group(function () {
            Route::get('/all', 'BrandController@index')->name('owner.allBrand');
            Route::get('/add', 'BrandController@addForm')->name('owner.addNewBrandForm');
            Route::post('/add', 'BrandController@store')->name('owner.addNewBrand');
            Route::get('/edit/{id}', 'BrandController@editForm')->name('owner.editBrand');
            Route::post('/update', 'BrandController@update')->name('owner.updateBrand');
            Route::get('/delete/{id}', 'BrandController@delete')->name('owner.deleteBrand');
        });

        Route::prefix('category')->group(function () {
            Route::get('/all', 'CategoryController@index')->name('owner.allCategory');
            Route::get('/add', 'CategoryController@addForm')->name('owner.addNewCategoryForm');
            Route::post('/add', 'CategoryController@store')->name('owner.addNewCategory');
            Route::get('/edit/{id}', 'CategoryController@editForm')->name('owner.editCategory');
            Route::post('/update', 'CategoryController@update')->name('owner.updateCategory');
            Route::get('/delete/{id}', 'CategoryController@delete')->name('owner.deleteCategory');
        });
		
        Route::prefix('product')->group(function () {
            Route::get('/all', 'ProductController@index')->name('owner.allProduct');
            Route::get('/add', 'ProductController@addForm')->name('owner.addNewProductForm');
            Route::post('/add', 'ProductController@store')->name('owner.addNewProduct');
            Route::get('/show/{id}', 'ProductController@show')->name('owner.allProductShow');
            Route::get('/edit/{id}', 'ProductController@editForm')->name('owner.editProduct');
            Route::post('/update', 'ProductController@update')->name('owner.updateProduct');
            Route::get('/delete/{id}', 'ProductController@delete')->name('owner.deleteProduct');
            
            Route::get('/import_product', 'ProductController@importProductList')->name('owner.importProductList');
            Route::get('/import', 'ProductController@importProduct')->name('owner.importProduct');
            Route::get('/generatelabel', 'ProductController@productForLabel')->name('owner.productlistlabel');
            Route::get('/barcodeprintpreview', 'ProductController@barcodePrintPreview')->name('owner.barcodeprintpreview');
            Route::get('/labelprint', 'ProductController@labelPrint')->name('owner.labelprint');
            Route::get('/qrcodeprintpreview', 'ProductController@qrcodePrintPreview')->name('owner.qrcodeprintpreview');
        });

        Route::prefix('company')->group(function () {
            Route::get('/all', 'CompanyController@index')->name('owner.allCompany');
            Route::get('/add', 'CompanyController@addForm')->name('owner.addNewCompanyForm');
            Route::post('/add', 'CompanyController@store')->name('owner.addNewCompany');
            Route::post('/update', 'CompanyController@update')->name('owner.updateCompany');
        });
        
        Route::prefix('branch')->group(function () {
            Route::get('/all', 'BranchController@index')->name('owner.allBranch');
            Route::get('/add', 'BranchController@addForm')->name('owner.addNewBranchForm');
            Route::post('/add', 'BranchController@store')->name('owner.addNewBranch');
            Route::get('/edit/{id}', 'BranchController@editForm')->name('owner.editBranch');
            Route::post('/update', 'BranchController@update')->name('owner.updateBranch');
            Route::get('/delete/{id}', 'BranchController@delete')->name('owner.deleteBranch');
        });
        
        Route::prefix('supplier')->group(function () {
            Route::get('/all', 'SupplierController@index')->name('owner.allSupplier');
            Route::get('/add', 'SupplierController@addForm')->name('owner.addNewSupplierForm');
            Route::post('/add', 'SupplierController@store')->name('owner.addNewSupplier');
            Route::get('/edit/{id}', 'SupplierController@editForm')->name('owner.editSupplier');
            Route::post('/update', 'SupplierController@update')->name('owner.updateSupplier');
            Route::get('/delete/{id}', 'SupplierController@delete')->name('owner.deleteSupplier');
        });
        
        Route::prefix('customer')->group(function () {
            Route::get('/all', 'CustomerController@index')->name('owner.allCustomer');
            Route::get('/add', 'CustomerController@addForm')->name('owner.addNewCustomerForm');
            Route::post('/add', 'CustomerController@store')->name('owner.addNewCustomer');
            Route::get('/edit/{id}', 'CustomerController@editForm')->name('owner.editCustomer');
            Route::post('/update', 'CustomerController@update')->name('owner.updateCustomer');
            Route::get('/delete/{id}', 'CustomerController@delete')->name('owner.deleteCustomer');
            Route::get('/search-due-customer', 'BillController@searchDueCustomerForm')->name('owner.searchDueCustomerForm');
            Route::post('/due-customer', 'BillController@searchDueCustomer')->name('owner.searchDueCustomer');
            Route::post('/due-pay', 'BillController@payDue')->name('owner.payDue');
        });
        
		
        Route::prefix('bill')->group(function () {
            Route::get('/all', 'BillController@index')->name('owner.allBill');
            Route::get('/getBatch', 'BillController@getBatch')->name('owner.getBatch');
            Route::get('/add', 'BillController@addForm')->name('owner.addNewBillForm');
            Route::post('/add', 'BillController@store')->name('owner.addNewBill');
            Route::get('/change_status', 'BillController@changeStatus')->name('owner.changeStatus');
            Route::get('/show/{id}', 'BillController@billShow')->name('owner.billShow');
            Route::get('/pdf_bill/{id}/{tp}', 'BillController@billPDF')->name('owner.billPDF');
            Route::get('/edit/{id}', 'BillController@editForm')->name('owner.edit');
            Route::post('/update', 'BillController@update')->name('owner.updateBill');
            Route::get('/delete/{id}', 'BillController@delete')->name('owner.deleteBill');
            Route::get('/saveCustomer', 'BillController@setCustomer')->name('owner.saveCustomer');
            /*All Customer Bill*/
			Route::get('/invoiceList/{id}', 'BillController@allInvoice')->name('owner.allInvoice');

            Route::get('/replace/{id}', 'BillController@replaceForm')->name('owner.replaceBillForm');
            Route::post('/breplace', 'BillController@replace')->name('owner.replaceBill');
            Route::get('/replaceall', 'BillController@replaceAll')->name('owner.allBillReplace');
            Route::get('/returnall', 'BillController@returnAll')->name('owner.allBillReturn');
        });
        
		
        Route::prefix('purchase')->group(function () {
            Route::get('/all', 'PurchaseController@index')->name('owner.allPurchase');
            Route::get('/setBatch', 'PurchaseController@setBatch')->name('owner.setBatch');
            Route::get('/add', 'PurchaseController@addForm')->name('owner.addNewPurchaseForm');
            Route::post('/add', 'PurchaseController@store')->name('owner.addNewPurchase');
            Route::get('/change_status', 'PurchaseController@changeStatus')->name('owner.changePurchaseStatus');
            Route::get('/show/{id}', 'PurchaseController@show')->name('owner.purchaseShow');
            Route::get('/pdf_bill/{id}/{tp}', 'PurchaseController@purchasePDF')->name('owner.purchasePDF');
            Route::get('/edit/{id}', 'PurchaseController@editForm')->name('owner.editPurchase');
            Route::post('/update', 'PurchaseController@update')->name('owner.updatePurchase');
            Route::get('/delete/{name}/{id}', 'PurchaseController@delete')->name('owner.deletePurchase');
            Route::get('/saveSupplier', 'PurchaseController@setSupplier')->name('owner.saveSupplier');
            Route::get('/saveProduct', 'PurchaseController@setProduct')->name('owner.saveProduct');
            Route::get('/getProduct', 'PurchaseController@getProduct')->name('owner.getProduct');
            Route::get('/getProductDetails', 'PurchaseController@getProductDetails')->name('owner.getProductDetails');
            Route::get('/saveProductDetails', 'PurchaseController@setProductDetails')->name('owner.saveProductDetails');

            Route::get('/replace/{id}', 'PurchaseController@replaceForm')->name('owner.replacePurchaseForm');
            Route::post('/replace', 'PurchaseController@replace')->name('owner.replacePurchase');
            Route::get('/replaceall', 'PurchaseController@replaceAll')->name('owner.allPurchaseReplace');
            Route::get('/returnall', 'PurchaseController@returnAll')->name('owner.allPurchaseReturn');
        });
        
         /*Warrenty For Owner */
        Route::prefix('warrenty')->group(function () {
            Route::get('/all', 'WarrentyController@index')->name('owner.allWarrenty');
            Route::get('/productDetails', 'WarrentyController@productDetails')->name('owner.productDetails');
            Route::get('/customerDetails', 'WarrentyController@customerDetails')->name('owner.customerDetails');
            Route::get('/add', 'WarrentyController@addForm')->name('owner.addNew_WarrentyForm');
            Route::post('/add', 'WarrentyController@store')->name('owner.addNewWarrenty');
            Route::get('/show/{id}', 'WarrentyController@show')->name('owner.warrentyShow');
            Route::post('/update', 'WarrentyController@update')->name('owner.updateWarrenty');
        });

        /*Service For Owner */
        Route::prefix('service')->group(function () {
            Route::get('/all', 'ServiceController@index')->name('owner.allService');
            Route::get('/productDetails', 'ServiceController@productDetails')->name('owner.serviceproductDetails');
            Route::get('/add', 'ServiceController@addForm')->name('owner.addNew_ServiceForm');
            Route::post('/add', 'ServiceController@store')->name('owner.addNewService');
            Route::get('/show/{id}', 'ServiceController@show')->name('owner.serviceShow');
            Route::post('/update', 'ServiceController@update')->name('owner.updateService');
        });
        
        
        Route::prefix('stock')->group(function () {
            Route::get('/all', 'StockConroller@index')->name('owner.allStockM');
            Route::get('/change_status', 'StockConroller@changeStatus')->name('owner.changeStatusM');
        });
        
        Route::prefix('report')->group(function () {
            Route::get('/stock/batch', 'ReportController@stockBatch')->name('owner.StockBatch');
            Route::get('/purchase', 'ReportController@purchaseReport')->name('owner.PurchaseReport');
            Route::get('/sales', 'ReportController@salesReport')->name('owner.allSalesReport');
        });
        
        
		Route::prefix('package')->group(function () {
            Route::get('/show', 'PackageController@requestPackage')->name('owner.requestPackage');
            Route::get('/req/{id}', 'PackageController@spr')->name('owner.sendPackageRequest');
            Route::get('/coupon_check', 'PackageController@coupon_check')->name('owner.checkCoupon');
            Route::get('/cancel_package/{id}', 'PackageController@cancelPackage')->name('owner.cancelPackage');
            Route::get('/my_package', 'PackageController@myPackage')->name('owner.myPackage');
        });
    });
});

// Sales Manager
Route::group(['middleware' => 'isSalesManager'], function(){
    Route::prefix('sales-manager')->group(function () {
        Route::get('/', 'DashboardController@salesManager');
        Route::get('/dashboard', 'DashboardController@salesManager')->name('salesmanagerDashboard');

        Route::prefix('user')->group(function () {
            Route::get('/all', 'UserController@index')->name('salesmanager.allUser');
            Route::get('/add', 'UserController@addForm')->name('salesmanager.addNewUserForm');
            Route::post('/add', 'UserController@store')->name('salesmanager.addNewUser');
            Route::get('/edit/{name}/{id}', 'UserController@editForm')->name('salesmanager.editUser');
            Route::post('/update', 'UserController@update')->name('salesmanager.updateUser');
            Route::get('/delete/{name}/{id}', 'UserController@delete')->name('salesmanager.deleteUser');
            
            Route::get('/profile', 'UserController@userProfile')->name('salesmanager.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('salesmanager.storeProfile');
            
            Route::post('/changePass', 'UserController@changePass')->name('salesmanager.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('salesmanager.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('salesmanager.changeAcc');
        });
		
		
        Route::prefix('medicine')->group(function () {
            Route::get('/all', 'ProductController@index')->name('salesmanager.allProduct');
            Route::get('/list-quantity/{name}/{id}', 'ProductController@productQuantityList')->name('salesmanager.productQuantityList');
            Route::get('/import_medicine', 'ProductController@importMedicineList')->name('salesmanager.importMedicineList');
            Route::get('/import', 'ProductController@importMedicine')->name('salesmanager.importMedicine');
            Route::get('/edit/{id}', 'ProductController@editForm')->name('salesmanager.editProduct');
            Route::post('/update', 'ProductController@update')->name('salesmanager.updateProduct');
		   
		   
            Route::get('/all_request', 'MedicineRequestController@index')->name('salesmanager.allRequest');
            Route::get('/add_request', 'MedicineRequestController@create')->name('salesmanager.addNewRequetForm');
            Route::post('/add_request', 'MedicineRequestController@store')->name('salesmanager.addNewRequet');
            Route::get('/edit_request/{id}', 'MedicineRequestController@edit')->name('salesmanager.editRequest');
            Route::post('/update_request', 'MedicineRequestController@update')->name('salesmanager.updateRequest');
            Route::get('/delete_request/{id}', 'MedicineRequestController@delete')->name('salesmanager.deleteRequest');
            
        });

        Route::prefix('company')->group(function () {
            Route::get('/all', 'CompanyController@index')->name('salesmanager.allCompany');
            Route::get('/add', 'CompanyController@addForm')->name('salesmanager.addNewCompanyForm');
            Route::post('/add', 'CompanyController@store')->name('salesmanager.addNewCompany');
            Route::post('/update', 'CompanyController@update')->name('salesmanager.updateCompany');
        });
        
        Route::prefix('supplier')->group(function () {
            Route::get('/all', 'SupplierController@index')->name('salesmanager.allSupplier');
            Route::get('/add', 'SupplierController@addForm')->name('salesmanager.addNewSupplierForm');
            Route::post('/add', 'SupplierController@store')->name('salesmanager.addNewSupplier');
            Route::get('/edit/{id}', 'SupplierController@editForm')->name('salesmanager.editSupplier');
            Route::post('/update', 'SupplierController@update')->name('salesmanager.updateSupplier');
            Route::get('/delete/{name}/{id}', 'SupplierController@delete')->name('salesmanager.deleteSupplier');
        });
        
        Route::prefix('customer')->group(function () {
            Route::get('/all', 'CustomerController@index')->name('salesmanager.allCustomer');
            Route::get('/add', 'CustomerController@addForm')->name('salesmanager.addNewCustomerForm');
            Route::post('/add', 'CustomerController@store')->name('salesmanager.addNewCustomer');
            Route::get('/edit/{id}', 'CustomerController@editForm')->name('salesmanager.editCustomer');
            Route::post('/update', 'CustomerController@update')->name('salesmanager.updateCustomer');
            Route::get('/delete/{id}', 'CustomerController@delete')->name('salesmanager.deleteCustomer');
            Route::get('/search-due-customer', 'BillController@searchDueCustomerForm')->name('salesmanager.searchDueCustomerForm');
            Route::post('/due-customer', 'BillController@searchDueCustomer')->name('salesmanager.searchDueCustomer');
            Route::post('/due-pay', 'BillController@payDue')->name('salesmanager.payDue');
        });
        
        Route::prefix('bill')->group(function () {
            Route::get('/all', 'BillController@index')->name('salesmanager.allBill');
            Route::get('/getBatch', 'BillController@getBatch')->name('salesmanager.getBatch');
            Route::get('/add', 'BillController@addForm')->name('salesmanager.addNewBillForm');
            Route::post('/add', 'BillController@store')->name('salesmanager.addNewBill');
            Route::get('/change_status', 'BillController@changeStatus')->name('salesmanager.changeStatus');
            Route::get('/show/{id}', 'BillController@billShow')->name('salesmanager.billShow');
            Route::get('/pdf_bill/{id}/{tp}', 'BillController@billPDF')->name('salesmanager.billPDF');
            Route::get('/edit/{id}', 'BillController@editForm')->name('salesmanager.editBill');
            Route::post('/update', 'BillController@update')->name('salesmanager.updateBill');
            Route::get('/delete/{id}', 'BillController@delete')->name('salesmanager.deleteBill');
            Route::get('/saveCustomer', 'BillController@setCustomer')->name('salesmanager.saveCustomer');
            /*All Customer Bill*/
			Route::get('/invoiceList/{id}', 'BillController@allInvoice')->name('salesmanager.allInvoice');
        });
        
        Route::prefix('purchase')->group(function () {
           Route::get('/all', 'PurchaseController@index')->name('salesmanager.allPurchase');
            Route::get('/setBatch', 'PurchaseController@setBatch')->name('salesmanager.setBatch');
            Route::get('/add', 'PurchaseController@addForm')->name('salesmanager.addNewPurchaseForm');
            Route::post('/add', 'PurchaseController@store')->name('salesmanager.addNewPurchase');
            Route::get('/change_status', 'PurchaseController@changeStatus')->name('salesmanager.changePurchaseStatus');
            Route::get('/show/{id}', 'PurchaseController@show')->name('salesmanager.purchaseShow');
            Route::get('/pdf_bill/{id}/{tp}', 'PurchaseController@purchasePDF')->name('salesmanager.purchasePDF');
            Route::get('/edit/{name}/{id}', 'PurchaseController@editForm')->name('salesmanager.editPurchase');
            Route::post('/update', 'PurchaseController@update')->name('salesmanager.updatePurchase');
            Route::get('/delete/{name}/{id}', 'PurchaseController@delete')->name('salesmanager.deletePurchase');
            Route::get('/saveSupplier', 'PurchaseController@setSupplier')->name('salesmanager.saveSupplier');
        });
        
        /*Warrenty For SalesManager */
        Route::prefix('warrenty')->group(function () {
            Route::get('/all', 'WarrentyController@index')->name('salesmanager.allWarrenty');
            Route::get('/productDetails', 'WarrentyController@productDetails')->name('salesmanager.productDetails');
            Route::get('/customerDetails', 'WarrentyController@customerDetails')->name('salesmanager.customerDetails');
            Route::get('/add', 'WarrentyController@addForm')->name('salesmanager.addNew_WarrentyForm');
            Route::post('/add', 'WarrentyController@store')->name('salesmanager.addNewWarrenty');
            Route::get('/show/{id}', 'WarrentyController@show')->name('salesmanager.warrentyShow');
            Route::post('/update', 'WarrentyController@update')->name('salesmanager.updateWarrenty');
        });

        /*Service For SalesManager */
        Route::prefix('service')->group(function () {
            Route::get('/all', 'ServiceController@index')->name('salesmanager.allService');
            Route::get('/productDetails', 'ServiceController@productDetails')->name('salesmanager.serviceproductDetails');
            Route::get('/add', 'ServiceController@addForm')->name('salesmanager.addNew_ServiceForm');
            Route::post('/add', 'ServiceController@store')->name('salesmanager.addNewService');
            Route::get('/show/{id}', 'ServiceController@show')->name('salesmanager.serviceShow');
            Route::post('/update', 'ServiceController@update')->name('salesmanager.updateService');
        });
        
        
        
        Route::prefix('stock')->group(function () {
            Route::get('/all', 'StockConroller@index')->name('salesmanager.allStockM');
            Route::get('/change_status', 'StockConroller@changeStatus')->name('salesmanager.changeStatusM');
        });
        
        Route::prefix('report')->group(function () {
            Route::get('/stock/batch', 'ReportController@stockBatch')->name('salesmanager.StockBatch');
            Route::get('/all', 'ReportController@index')->name('salesmanager.allMedicineExpairy');
            Route::get('/all/purchase/report', 'ReportController@allpurchaseReport')->name('salesmanager.allPurchaseReport');
            Route::get('/all/sale/report', 'ReportController@allsaleReport')->name('salesmanager.allSaleReport');
            Route::get('/all/batch/report', 'ReportController@batchWiseReport')->name('salesmanager.allbatchWiseReport');
            Route::get('/all/sell/report/summary', 'ReportController@allSellReportSummary')->name('salesmanager.allSellReportSummary');
        });
		
    });
});

// Sales Man
Route::group(['middleware' => 'isSalesMan'], function(){
    Route::prefix('sales-man')->group(function () {
        Route::get('/', 'DashboardController@salesMan');
        Route::get('/dashboard', 'DashboardController@salesMan')->name('salesmanDashboard');
		
		Route::prefix('user')->group(function () {
            Route::get('/profile', 'UserController@userProfile')->name('salesman.userProfile');
            Route::post('/profile', 'UserController@storeProfile')->name('salesman.storeProfile');
            
            Route::post('/changePass', 'UserController@changePass')->name('salesman.changePass');
            Route::post('/changePer', 'UserController@changePer')->name('salesman.changePer');
            Route::post('/changeAcc', 'UserController@changeAcc')->name('salesman.changeAcc');
        });
		
        Route::prefix('medicine')->group(function () {
            Route::get('/all', 'ProductController@index')->name('salesman.allProduct');
           Route::get('/list-quantity/{name}/{id}', 'ProductController@productQuantityList')->name('salesman.productQuantityList');
           Route::get('/import_medicine', 'ProductController@importMedicineList')->name('salesman.importMedicineList');
           Route::get('/import', 'ProductController@importMedicine')->name('salesman.importMedicine');
            Route::get('/edit/{id}', 'ProductController@editForm')->name('salesman.editProduct');
            Route::post('/update', 'ProductController@update')->name('salesman.updateProduct');
		   
		   
            Route::get('/all_request', 'MedicineRequestController@index')->name('salesman.allRequest');
            Route::get('/add_request', 'MedicineRequestController@create')->name('salesman.addNewRequetForm');
            Route::post('/add_request', 'MedicineRequestController@store')->name('salesman.addNewRequet');
            Route::get('/edit_request/{id}', 'MedicineRequestController@edit')->name('salesman.editRequest');
            Route::post('/update_request', 'MedicineRequestController@update')->name('salesman.updateRequest');
            Route::get('/delete_request/{id}', 'MedicineRequestController@delete')->name('salesman.deleteRequest');
            
        });
		
		Route::prefix('bill')->group(function () {
            Route::get('/all', 'BillController@index')->name('salesman.allBill');
            Route::get('/getBatch', 'BillController@getBatch')->name('salesman.getBatch');
            Route::get('/add', 'BillController@addForm')->name('salesman.addNewBillForm');
            Route::post('/add', 'BillController@store')->name('salesman.addNewBill');
            Route::get('/change_status', 'BillController@changeStatus')->name('salesman.changeStatus');
            Route::get('/show/{id}', 'BillController@billShow')->name('salesman.billShow');
            Route::get('/pdf_bill/{id}/{tp}', 'BillController@billPDF')->name('salesman.billPDF');
            Route::get('/edit/{id}', 'BillController@editForm')->name('salesman.editBill');
            Route::post('/update', 'BillController@update')->name('salesman.updateBill');
            Route::get('/delete/{id}', 'BillController@delete')->name('salesman.deleteBill');
            Route::get('/saveCustomer', 'BillController@setCustomer')->name('salesman.saveCustomer');
            /*All Customer Bill*/
			Route::get('/invoiceList/{id}', 'BillController@allInvoice')->name('salesman.allInvoice');
        });
        
        Route::prefix('purchase')->group(function () {
            
           Route::get('/all', 'PurchaseController@index')->name('salesman.allPurchase');
            Route::get('/setBatch', 'PurchaseController@setBatch')->name('salesman.setBatch');
            Route::get('/add', 'PurchaseController@addForm')->name('salesman.addNewPurchaseForm');
            Route::post('/add', 'PurchaseController@store')->name('salesman.addNewPurchase');
            Route::get('/change_status', 'PurchaseController@changeStatus')->name('salesman.changePurchaseStatus');
            Route::get('/show/{id}', 'PurchaseController@show')->name('salesman.purchaseShow');
            Route::get('/pdf_bill/{id}/{tp}', 'PurchaseController@purchasePDF')->name('salesman.purchasePDF');
            Route::get('/edit/{name}/{id}', 'PurchaseController@editForm')->name('salesman.editPurchase');
            Route::post('/update', 'PurchaseController@update')->name('salesman.updatePurchase');
            Route::get('/delete/{name}/{id}', 'PurchaseController@delete')->name('salesman.deletePurchase');
            Route::get('/saveSupplier', 'PurchaseController@setSupplier')->name('salesman.saveSupplier');
        });
        
        
        
        /*Warrenty For Salesman */
        Route::prefix('warrenty')->group(function () {
            Route::get('/all', 'WarrentyController@index')->name('salesman.allWarrenty');
            Route::get('/productDetails', 'WarrentyController@productDetails')->name('salesman.productDetails');
            Route::get('/customerDetails', 'WarrentyController@customerDetails')->name('salesman.customerDetails');
            Route::get('/add', 'WarrentyController@addForm')->name('salesman.addNew_WarrentyForm');
            Route::post('/add', 'WarrentyController@store')->name('salesman.addNewWarrenty');
            Route::get('/show/{id}', 'WarrentyController@show')->name('salesman.warrentyShow');
            Route::post('/update', 'WarrentyController@update')->name('salesman.updateWarrenty');
        });

        /*Service For Owner */
        Route::prefix('service')->group(function () {
            Route::get('/all', 'ServiceController@index')->name('salesman.allService');
            Route::get('/productDetails', 'ServiceController@productDetails')->name('salesman.serviceproductDetails');
            Route::get('/add', 'ServiceController@addForm')->name('salesman.addNew_ServiceForm');
            Route::post('/add', 'ServiceController@store')->name('salesman.addNewService');
            Route::get('/show/{id}', 'ServiceController@show')->name('salesman.serviceShow');
            Route::post('/update', 'ServiceController@update')->name('salesman.updateService');
        });
        
        Route::prefix('supplier')->group(function () {
            Route::get('/all', 'SupplierController@index')->name('salesman.allSupplier');
            Route::get('/add', 'SupplierController@addForm')->name('salesman.addNewSupplierForm');
            Route::post('/add', 'SupplierController@store')->name('salesman.addNewSupplier');
            Route::get('/edit/{id}', 'SupplierController@editForm')->name('salesman.editSupplier');
            Route::post('/update', 'SupplierController@update')->name('salesman.updateSupplier');
            Route::get('/delete/{name}/{id}', 'SupplierController@delete')->name('salesman.deleteSupplier');
        });
        
        Route::prefix('customer')->group(function () {
            Route::get('/all', 'CustomerController@index')->name('salesman.allCustomer');
            Route::get('/add', 'CustomerController@addForm')->name('salesman.addNewCustomerForm');
            Route::post('/add', 'CustomerController@store')->name('salesman.addNewCustomer');
            Route::get('/edit/{id}', 'CustomerController@editForm')->name('salesman.editCustomer');
            Route::post('/update', 'CustomerController@update')->name('salesman.updateCustomer');
            Route::get('/delete/{id}', 'CustomerController@delete')->name('salesman.deleteCustomer');
            Route::get('/search-due-customer', 'BillController@searchDueCustomerForm')->name('salesman.searchDueCustomerForm');
            Route::post('/due-customer', 'BillController@searchDueCustomer')->name('salesman.searchDueCustomer');
            Route::post('/due-pay', 'BillController@payDue')->name('salesman.payDue');
        });
        
        Route::prefix('stock')->group(function () {
            Route::get('/all', 'StockConroller@index')->name('salesman.allStockM');
            Route::get('/change_status', 'StockConroller@changeStatus')->name('salesman.changeStatusM');
        });
        
    });
});
