<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
	  	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>E-Cash - @yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Lead By Md Shafiul Islam, Co-Lead By Md Tawhidul Alam">
    

    <link rel="apple-touch-icon" href="{{asset('/')}}assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/')}}assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/calendars/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/extensions/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/file-uploaders/dropzone.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/themes/dark-layout.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/themes/bordered-layout.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/themes/semi-dark-layout.min.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/plugins/charts/chart-apex.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/plugins/extensions/ext-component-toastr.min.css">
	<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/plugins/forms/form-file-uploader.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/style.css">
    <!-- END: Custom CSS-->
	<!--begin::Page Style(used by this page)-->
	@stack('styles')
	<!--end::Page Style-->

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light">
      <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
          <ul class="nav navbar-nav d-xl-none">
            <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
          </ul>
          <ul class="nav navbar-nav bookmark-icons">
            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ encryptor('decrypt', Session::get('email')) }}"><i class="ficon" data-feather="mail"></i></a></li>
            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ encryptor('decrypt', Session::get('mobileNumber')) }}"><i class="ficon" data-feather="phone"></i></a></li>
          </ul>
          
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
          <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{ encryptor('decrypt', Session::get('username')) }}</span><span class="user-status">{{ encryptor('decrypt', Session::get('shopCode')) }}</span></div>
              <span class="avatar"><img class="round" src="{{asset('/')}}storage/images/user/photo/{{ Session::get('uphoto') }}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span></a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
              <a class="dropdown-item" href="{{route(currentUser().'.userProfile')}}"><i class="me-50" data-feather="user"></i> Profile</a>
              @if(currentUser() === 'owner' || currentUser() === 'salesmanager')
              <a class="dropdown-item" href="{{route(currentUser().'.allCompany')}}"><i class="me-50" data-feather="settings"></i> Settings</a>
              @endif
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{route('logOut')}}"><i class="me-50" data-feather="power"></i> Logout</a>
            </div>
			<!--profile target _blank medbill a chilo-->
          </li>
        </ul>
      </div>
    </nav>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item me-auto"><a class="navbar-brand" href="#"><span class="brand-logo">
            <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
              <defs>
                <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                  <stop stop-color="#000000" offset="0%"></stop>
                  <stop stop-color="#FFFFFF" offset="100%"></stop>
                </lineargradient>
                <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                  <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                  <stop stop-color="#FFFFFF" offset="100%"></stop>
                </lineargradient>
              </defs>
              <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                  <g id="Group" transform="translate(400.000000, 178.000000)">
                    <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                    <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                    <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                    <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                    <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                  </g>
                </g>
              </g>
            </svg></span>
            <h2 class="brand-text">E-Cash</h2></a>
          </li>
          <li class="nav-item nav-toggle">
            <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
              <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
              <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="shadow-bottom"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
          
          <li class="nav-item {{menuActive(currentUser().'Dashboard')}}"><a class="d-flex align-items-center" href="{{route(currentUser().'Dashboard')}}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span></a></li>
          
          @if(currentUser() == 'superadmin')
            <li class="nav-item {{menuActive(currentUser().'.allPackage')}} {{menuActive(currentUser().'.addNewPackageForm')}}  {{menuActive(currentUser().'.editPackage')}}">
              <a class="d-flex align-items-center" href="@if(currentUser() === 'superadmin') {{route(currentUser().'.allPackage')}} @endif">
                <i data-feather="user"></i>
                <span class="menu-title text-truncate" data-i18n="Customer">Package</span>
              </a>
            </li>
          @endif
          @if(currentUser() == 'superadmin')
            <li class="nav-item {{menuActive(currentUser().'.allCoupon')}} {{menuActive(currentUser().'.addNewCouponForm')}}  {{menuActive(currentUser().'.editCoupon')}}">
              <a class="d-flex align-items-center" href="@if(currentUser() === 'superadmin') {{route(currentUser().'.allCoupon')}} @endif">
                <i data-feather="user"></i>
                <span class="menu-title text-truncate" data-i18n="Customer">Coupon</span>
              </a>
            </li>
          @endif
          @if(currentUser() == 'superadmin')
            <li class="nav-item {{menuActive(currentUser().'.reqPackage',3)}} {{menuActive(currentUser().'.reqPackagepending',3)}}"><a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="tv"></i><span class="menu-title text-truncate" data-i18n="Employees">Package Request</span></a>
              <ul class="menu-content">
                  <li class="{{menuActive(currentUser().'.reqPackage')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'superadmin') {{route(currentUser().'.reqPackage')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">All Request</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.reqPackagepending')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'superadmin') {{route(currentUser().'.reqPackagepending')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Pending Request</span>
                    </a>
                  </li>
                </ul>
            </li>
          @endif
          
          @if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() === 'salesman')
            <li class="nav-item {{menuActive(currentUser().'.allCustomer')}} {{menuActive(currentUser().'.addNewCustomerForm')}}  {{menuActive(currentUser().'.editCustomer')}}">
              <a class="d-flex align-items-center" href="@if(currentUser() === 'owner' || currentUser() === 'salesmanager' || currentUser() === 'salesman') {{route(currentUser().'.allCustomer')}} @endif">
                <i data-feather="user"></i>
                <span class="menu-title text-truncate" data-i18n="Customer">Customer</span>
              </a>
            </li>
          @endif

          @if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() === 'salesman')
            <li class="nav-item {{menuActive(currentUser().'.allSupplier')}} {{menuActive(currentUser().'.addNewSupplierForm')}}  {{menuActive(currentUser().'.editSupplier')}}">
              <a class="d-flex align-items-center" href="@if(currentUser() === 'owner' || currentUser() === 'salesmanager' || currentUser() === 'salesman') {{route(currentUser().'.allSupplier')}} @endif">
                <i data-feather="user"></i>
                <span class="menu-title text-truncate" data-i18n="Supplier">Supplier</span>
              </a>
            </li>
          @endif
          
          @if(currentUser() == 'owner' || currentUser() == 'salesmanager')
            <li class="nav-item {{menuActive(currentUser().'.*',3)}}"><a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="tv"></i><span class="menu-title text-truncate" data-i18n="Employees">Product</span></a>
              <ul class="menu-content">
                  <li class="{{menuActive(currentUser().'.allProduct')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.allProduct')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Product List</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.importProductList')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.importProductList')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Import Now</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allBrand')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.allBrand')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Brand</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allCategory')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.allCategory')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Category</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.productlistbarcode')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.productlistbarcode')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Generate Barcode</span>
                    </a>
                  </li>
                </ul>
            </li>
          @endif
          
          @if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman')
            <li class="nav-item {{menuActive(currentUser().'.*',3)}}">
              <a class="d-flex align-items-center" href="javascript:void(0)">
                <i data-feather="tv"></i>
                <span class="menu-title text-truncate" data-i18n="Employees">Purchase</span>
              </a>
              <ul class="menu-content">
                  <li class="{{menuActive(currentUser().'.allPurchase')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allPurchase')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">List</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.addNewPurchaseForm')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.addNewPurchaseForm')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add New</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allPurchaseReplace')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allPurchaseReplace')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Replace List</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allPurchaseReturn')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allPurchaseReturn')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Return List</span>
                    </a>
                  </li>
                </ul>
            </li>
          @endif
          
          @if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman')
            <li class="nav-item {{menuActive(currentUser().'.*',3)}}"><a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="tv"></i><span class="menu-title text-truncate" data-i18n="Employees">Sell</span></a>
              <ul class="menu-content">
                  <li class="{{menuActive(currentUser().'.allBill')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allBill')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">List</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.addNewBillForm')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.addNewBillForm')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add New</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allBillReplace')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allBillReplace')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Replace List</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allBillReturn')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allBillReturn')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Return List</span>
                    </a>
                  </li>
                </ul>
            </li>
          @endif
          
          @if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman')
          <li class="nav-item {{menuActive(currentUser().'.allService')}} {{menuActive(currentUser().'.addNew_ServiceForm')}}  {{menuActive(currentUser().'.editUser')}}">
              <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allService')}} @endif">
                <i data-feather="tv"></i>
                <span class="menu-title text-truncate" data-i18n="User">Service</span>
              </a>
            </li>
          @endif
          
          @if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman')
          <li class="nav-item {{menuActive(currentUser().'.allWarrenty')}}">
              <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager' || currentUser() == 'salesman') {{route(currentUser().'.allWarrenty')}} @endif">
                <i data-feather="tv"></i>
                <span class="menu-title text-truncate" data-i18n="User">Warrenty</span>
              </a>
            </li>
          @endif

          @if(currentUser() == 'dataentry')
            <li class="nav-item {{menuActive(currentUser().'.*',3)}}"><a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="tv"></i><span class="menu-title text-truncate" data-i18n="Employees">Product</span></a>
              <ul class="menu-content">
                  <li class="{{menuActive(currentUser().'.allProduct')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'dataentry') {{route(currentUser().'.allProduct')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">List</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.addNewProductForm')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'dataentry') {{route(currentUser().'.addNewProductForm')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add New</span>
                    </a>
                  </li>
                  
                  <li class="{{menuActive(currentUser().'.allBrand')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'dataentry') {{route(currentUser().'.allBrand')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Brand</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allCategory')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'dataentry') {{route(currentUser().'.allCategory')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Category</span>
                    </a>
                  </li>
                </ul>
            </li>
          @endif
          
          @if(currentUser() == 'owner' || currentUser() == 'salesmanager')
            <li class="nav-item {{menuActive(currentUser().'.*',3)}}"><a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Employees">Shop Management</span></a>
              <ul class="menu-content">
                  
                  <li class="{{menuActive(currentUser().'.allUser')}} {{menuActive(currentUser().'.addNewUserForm')}}  {{menuActive(currentUser().'.editUser')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.allUser')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">User</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allBranch')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.allBranch')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Branch</span>
                    </a>
                  </li>
                  <li class="{{menuActive(currentUser().'.allCompany')}}">
                    <a class="d-flex align-items-center" href="@if(currentUser() == 'owner' || currentUser() == 'salesmanager') {{route(currentUser().'.allCompany')}} @endif">
                      <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Company</span>
                    </a>
                  </li>
                  @if(currentUser() == 'owner')
                      <li class="{{menuActive(currentUser().'.requestPackage')}}">
                        <a class="d-flex align-items-center" href="@if(currentUser() == 'owner') {{route(currentUser().'.requestPackage')}} @endif">
                          <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Buy Package</span>
                        </a>
                      </li>
                      <li class="{{menuActive(currentUser().'.myPackage')}}">
                        <a class="d-flex align-items-center" href="@if(currentUser() == 'owner') {{route(currentUser().'.myPackage')}} @endif">
                          <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">My Packages</span>
                        </a>
                      </li>
                  @endif
          
                </ul>
            </li>
          @endif
          
           @if(currentUser() == 'marketingmanager' || currentUser() == 'superadmin' || currentUser() == 'executive')
            <li class="nav-item {{menuActive(currentUser().'.allUser')}} {{menuActive(currentUser().'.addNewUserForm')}}  {{menuActive(currentUser().'.editUser')}}">
              <a class="d-flex align-items-center" href="@if(currentUser() == 'marketingmanager' || currentUser() == 'superadmin' || currentUser() == 'executive') {{route(currentUser().'.allUser')}} @endif">
                <i data-feather="users"></i>
                <span class="menu-title text-truncate" data-i18n="User">User</span>
              </a>
            </li>
          @endif
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-overlay"></div>
            <div class="content-body">
                @yield('content')
            </div>
    </div>
    <!-- END: Content-->

    

    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
      <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT  &copy; 2021<a class="ms-25" href="{{route(currentUser().'Dashboard')}}" target="_blank">E-Cash</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span><span class="float-md-end d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('/')}}assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    {{--<script src="{{asset('/')}}assets/vendors/js/charts/apexcharts.min.js"></script>--}}
	<script src="{{asset('/')}}assets/vendors/js/calendar/fullcalendar.min.js"></script>
    <script src="{{asset('/')}}assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="{{asset('/')}}assets/vendors/js/extensions/moment.min.js"></script>
    <script src="{{asset('/')}}assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{asset('/')}}assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{asset('/')}}assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
	<script src="{{asset('/')}}assets/vendors/js/file-uploaders/dropzone.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('/')}}assets/js/core/app-menu.min.js"></script>
    <script src="{{asset('/')}}assets/js/core/app.min.js"></script>
    <script src="{{asset('/')}}assets/js/scripts/customizer.min.js"></script>
     <!-- BEGIN: Page JS-->
     <script src="{{asset('/')}}assets/js/scripts/components/components-alerts.js"></script>
     <!-- END: Page JS-->
    <!-- END: Theme JS-->
    {{--<script src="{{asset('/')}}assets/js/scripts/pages/dashboard-analytics.min.js"></script>--}}
	<script src="{{asset('/')}}assets/js/scripts/forms/form-file-uploader.min.js"></script>
    <!-- END: Page JS-->

	<!--begin::Page Scripts(used by this page)-->
	@stack('scripts')
	<!--end::Page Scripts-->
    <script>
      $(window).on('load',  function(){
        if (feather) {
          feather.replace({ width: 14, height: 14 });
        }
        $('.btn-close').click( function(){
          $('.alert-dismissible').hide()
        })
      });
    </script>
  </body>
  <!-- END: Body-->
</html>
