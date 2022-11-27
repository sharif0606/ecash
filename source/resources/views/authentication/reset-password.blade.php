@extends('layout.auth_master')

@section('title', 'Forget Password')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="auth-wrapper auth-v1 px-2">
                <div class="auth-inner py-2">
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="#" class="brand-logo">
                                <svg
                                viewbox="0 0 139 95"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                height="28"
                                >
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
                                        <path
                                        class="text-primary"
                                        id="Path"
                                        d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                        style="fill: currentColor"
                                        ></path>
                                        <path
                                        id="Path1"
                                        d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                        fill="url(#linearGradient-1)"
                                        opacity="0.2"
                                        ></path>
                                        <polygon
                                        id="Path-2"
                                        fill="#000000"
                                        opacity="0.049999997"
                                        points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"
                                        ></polygon>
                                        <polygon
                                        id="Path-21"
                                        fill="#000000"
                                        opacity="0.099999994"
                                        points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"
                                        ></polygon>
                                        <polygon
                                        id="Path-3"
                                        fill="url(#linearGradient-2)"
                                        opacity="0.099999994"
                                        points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"
                                        ></polygon>
                                    </g>
                                    </g>
                                </g>
                                </svg>
                                <h2 class="brand-text text-primary ms-1">Cashbaksho</h2>
                            </a>
                    
                            <h4 class="card-title mb-1">Welcome to Cashbaksho!</h4>
                            <p class="card-text mb-2">Reset Password</p>
                            <form class="auth-login-form mt-2" action="{{route('resetPasswordForm')}}" method="POST">
                                @csrf
            					@if( Session::has('response') )
                                    <div class="alert alert-{{Session::get('response')['class']}}" role="alert">
                                        {{Session::get('response')['message']}}
                                    </div>
                                @endif
                                
                                <div class="mb-1">
                                <label for="forget_code" class="form-label">OTP</label>
                                <input
                                    type="text"
                                    class="form-control @if($errors->has('forget_code')) {{'is-invalid'}} @endif" id="forget_code"
                                    placeholder="Mobile Number"
                                    name="forget_code"
                                    aria-describedby="forget_code"
                                    tabindex="1"
                                    autofocus
                                    value="{{old('forget_code')}}"
                                    />
                                </div>
                                @if($errors->has('forget_code'))
                                    <small class="d-block text-danger mb-3">
                                        {{ $errors->first('forget_code') }}
                                    </small>     
                                @endif 
                                <div class="mb-1">
                                    <label for="password" class="form-label">Password</label>
                                    <input
                                        type="password"
                                        class="form-control @if($errors->has('password')) {{'is-invalid'}} @endif" id="password"
                                        placeholder="Password"
                                        name="password"
                                        aria-describedby="password"
                                        tabindex="1"
                                        autofocus
                                        value="{{old('password')}}"
                                        />
                                </div>
                                @if($errors->has('password'))
                                    <small class="d-block text-danger mb-3">
                                        {{ $errors->first('password') }}
                                    </small>     
                                @endif  
                                
                                <div class="mb-1">
                                    <label for="password_confirmation" class="form-label">Retype Password</label>
                                    <input
                                        type="password"
                                        class="form-control @if($errors->has('password_confirmation')) {{'is-invalid'}} @endif" id="password_confirmation"
                                        placeholder="Enter Your Password Again"
                                        name="password_confirmation"
                                        aria-describedby="password_confirmation"
                                        tabindex="1"
                                        autofocus
                                        value="{{old('password_confirmation')}}"
                                        />
                                </div>
                                
					            <button type="submit" class="btn btn-primary w-100">Get OTP</button>
                                <p class="text-center mt-2">
                                    <span>I've an account </span>
                                    <a href="{{route('signInForm')}}">
                                    <span>Login</span>
                                    </a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layout.auth_master')

@section('title', 'Sign In')

@section('content')

<!-- Log In page -->
    <div class="row vh-100 ">
        <div class="col-12 align-self-center">
            <div class="auth-page">
                <div class="card auth-card shadow-lg">
                    <div class="card-body">
                        <div class="px-3">
                            <div class="auth-logo-box">
                                <a href="{{asset('/')}}analytics/analytics-index.html" class="logo logo-admin"><img src="{{asset('/')}}assets/images/logo-sm.png" height="55" alt="logo" class="auth-logo"></a>
                            </div><!--end auth-logo-box-->
                            
                            <div class="text-center auth-logo-text">
                                <h4 class="mt-0 mb-3 mt-5">Let's Get Started Metrica</h4>
                                <p class="text-muted mb-0">Sign in to continue to Metrica.</p>  
                            </div> <!--end auth-logo-text-->  

                            
                        <form class="form-horizontal auth-form my-4" action="{{route('resetPasswordForm')}}" method="POST">
                                @csrf
                                @if( Session::has('response') )
                                    <div class="alert alert-{{Session::get('response')['class']}}" role="alert">
                                        {{Session::get('response')['message']}}
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="userpassword">Password</label>                                            
                                    <div class="input-group mb-3"> 
                                        <span class="auth-form-icon">
                                            <i class="dripicons-lock"></i> 
                                        </span>                                                       
                                        <input type="password" name="password" value="{{old('password')}}" class="form-control @if($errors->has('password')) {{'is-invalid'}} @endif" id="userpassword" placeholder="Enter password">
                                    </div>    
                                    @if($errors->has('password'))
                                        <small class="d-block text-danger mb-3">
                                            {{ $errors->first('password') }}
                                        </small>     
                                    @endif                           
                                </div><!--end form-group--> 
    
                                <div class="form-group">
                                    <label for="userpassword">Confirm Password</label>                                            
                                    <div class="input-group mb-3"> 
                                        <span class="auth-form-icon">
                                            <i class="dripicons-lock"></i> 
                                        </span>                                                       
                                        <input type="password" name="password_confirmation" class="form-control" id="userpassword" placeholder="Enter confirm password">
                                    </div>                           
                                </div><!--end form-group--> 
    
                                <div class="form-group mb-0 row">
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">Reset Password <i class="fas fa-sign-in-alt ml-1"></i></button>
                                    </div><!--end col--> 
                                </div> <!--end form-group-->                           
                            </form><!--end form-->
                        </div><!--end /div-->
                    </div><!--end card-body-->
                </div><!--end card-->
                {{-- <div class="account-social text-center mt-4">
                    <h6 class="my-4">Or Login With</h6>
                    <ul class="list-inline mb-4">
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-facebook-f facebook"></i>
                            </a>                                    
                        </li>
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-twitter twitter"></i>
                            </a>                                    
                        </li>
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-google google"></i>
                            </a>                                    
                        </li>
                    </ul>
                </div> --}}
                <!--end account-social-->
            </div><!--end auth-page-->
        </div><!--end col-->           
    </div><!--end row-->
    <!-- End Log In page -->

@endsection
