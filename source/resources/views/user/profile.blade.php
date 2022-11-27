@extends('layout.admin.admin_master')
@section('title', 'User Profile')
@section('content')

	
<section class="app-user-view-account">
	@if( Session::has('response') )
	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<div class="card">
				<div class="card-body">
					<div class="demo-spacing-0">
						<div class="alert alert-primary" role="alert">
							<div class="alert-body"><strong>Success!</strong>{{Session::get('response')['message']}}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
  <div class="row">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
      <!-- User Card -->
      <div class="card">
        <div class="card-body">
          <div class="user-avatar-section">
            <div class="d-flex align-items-center flex-column">
				@php 
					$photo= $UserData->details->photo 
				@endphp
				@if($photo)
              	<img class="img-fluid rounded mt-3 mb-2" src="{{asset('storage/images/user/photo/'.$photo)}}" height="110" width="110" alt="user profile picture">
				@else
				<img class="img-fluid rounded mt-3 mb-2" src="avatar_default_image" height="110" width="110" alt="User avatar">
				@endif
				<div class="user-info text-center">
					<h4>{{ $UserData->name }}</h4>
					<span class="badge bg-light-secondary">{{ encryptor('decrypt', Session::get('username')) }}</span>
				</div>
            </div>
          </div>
          <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
          <div class="info-container">
            <ul class="list-unstyled">
              <li class="mb-75">
                <span class="fw-bolder me-25">Username:</span>
                <span>{{ currentUser() }}</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Billing Email:</span>
                <span>{{ $UserData->email }}</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Status:</span>
				<!--0 => inactive, 1 => active, 2 => pending, 3 => freez, 4 => block-->
				@if($UserData->status == 0)
                <span class="badge bg-light-danger">Inactive</span>
				@elseif($UserData->status == 1)
				<span class="badge bg-light-success">Active</span>
				@elseif($UserData->status == 2)
				<span class="badge bg-light-success">Pending</span>
				@elseif($UserData->status == 3)
				<span class="badge bg-light-success">freeze</span>
				@else
				<span class="badge bg-light-danger">Block</span>
				@endif
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Role:</span>
                <span>Author</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Contact:</span>
                <span>{{ $UserData->mobileNumber }}</span>
              </li>
              <li class="mb-75">
                <span class="fw-bolder me-25">Address:</span>
                <span>{{ $UserData->address }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /User Card -->
    </div>
    <!--/ User Sidebar -->
    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
      <!-- User Pills -->
      <ul class="nav nav-pills mb-2">
        {{--<li class="nav-item">
          <a class="nav-link" id="account-tab" data-bs-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user font-medium-3 me-50"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span class="fw-bold">Profile OverView</span></a>
        </li>--}}
		<li class="nav-item">
          <a class="nav-link active d-flex align-items-center" id="information-tab" data-bs-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="true">
            <i data-feather="info"></i><span class="d-none d-sm-block">Edit Profile</span>
          </a>
        </li>
		<li class="nav-item">
          <a class="nav-link d-flex align-items-center" id="change-password-tab" data-bs-toggle="tab" href="#password" aria-controls="changepassword" role="tab" aria-selected="false">
            <i data-feather="info"></i><span class="d-none d-sm-block">Change Password</span>
          </a>
        </li>
      </ul>
      <!--/ User Pills -->

	  <div class="tab-content">
        <!-- Account Overview Tab starts -->
        {{--<div class="tab-pane" id="account" aria-labelledby="account-tab" role="tabpanel"></div>--}}
        <!-- Account Overview Tab ends -->	
		<!-- Edit Profile Tab starts -->
		<div class="tab-pane active" id="information" aria-labelledby="information-tab" role="tabpanel">
			<div class="card">
				<div class="card-body">
					<form action="{{route(currentUser().'.changePer')}}"  method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="id" value="{{ encryptor('encrypt', $UserData->id) }}">
						<div class="d-flex mb-2">
							<img src="{{asset('storage/images/user/photo/'.$photo)}}" alt="users avatar" class="user-avatar users-avatar-shadow rounded me-2 my-25 cursor-pointer" height="90" width="90">
							<div class="mt-50">
								<h4>Eleanor Aguilar</h4>
								<div class="col-12 d-flex mt-1 px-0">
									<label class="btn btn-primary me-75 mb-0 waves-effect waves-float waves-light" for="change-picture">
										<span class="d-none d-sm-block">Change</span>
										<input class="form-control" name="photo" type="file" id="change-picture" hidden="" accept="image/png, image/jpeg, image/jpg">
										<span class="d-block d-sm-none">
											<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit me-0"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
										</span>
									</label>
									<button class="btn btn-outline-secondary d-none d-sm-block waves-effect">Remove</button>
									<button class="btn btn-outline-secondary d-block d-sm-none waves-effect">
										<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-0"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
									</button>
								</div>
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-12">
								<h4 class="mb-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user font-medium-4 me-25"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
									<span class="align-middle">Personal Information</span>
								</h4>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="mb-1">
									<label class="form-label" for="nid">NID</label>
									<input id="nid" type="text" class="form-control" placeholder="Nid here..." value="{{$UserData->details->nid}}" name="nid">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="mb-1">
									<label class="form-label" for="website">Name</label>
									<input id="nid" type="text" class="form-control" placeholder="Name here..." value="{{$UserData->name}}" name="name">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="mb-1">
									<label class="form-label" for="mobile">Mobile</label>
									<input id="mobile" type="text" class="form-control" name="mobileNumber" value="{{$UserData->mobileNumber}}">
								</div>
								@if($errors->has('mobileNumber'))
								<div class="demo-spacing-0">
									<div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
										<div class="alert-body d-flex align-items-center">
											<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info me-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
											<span>{{ $errors->first('mobileNumber') }}</span>
										</div>
									</div>
								</div>
								@endif
							</div>
							<div class="col-12">
								<h4 class="mb-1 mt-2">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user font-medium-4 me-25"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
									<span class="align-middle">Account</span>
								</h4>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="mb-1">
								<label class="form-label" for="mobile">UserName</label>
									<input class="form-control" type="text" name="username" value="{{$UserData->username}}" readonly>
								</div>
								@if($errors->has('username'))
								<div class="demo-spacing-0">
									<div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
										<div class="alert-body d-flex align-items-center">
											<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info me-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
											<span>{{ $errors->first('username') }}</span>
										</div>
									</div>
								</div>
								@endif
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="mb-1">
									<label class="form-label" for="mobile">Email</label>
									<input id="email" type="text" class="form-control" name="email" value="{{$UserData->email}}" readonly>
								</div>
								@if($errors->has('email'))
								<div class="demo-spacing-0">
									<div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
										<div class="alert-body d-flex align-items-center">
											<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info me-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
											<span>{{ $errors->first('email') }}</span>
										</div>
									</div>
								</div>
								@endif
							</div>
							<div class="col-12">
								<h4 class="mb-1 mt-2">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin font-medium-4 me-25"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
									<span class="align-middle">Address</span>
								</h4>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="mb-1">
									<label class="form-label" for="address-1">Address Line 1</label>
									<textarea class="form-control" rows="5" name="address" placeholder="Address">{{$UserData->details->address}}</textarea>
								</div>
							</div>
							<div class="col-12 d-flex flex-sm-row flex-column mt-2">
								<button type="submit" class="btn btn-primary mb-1 mb-sm-0 me-0 me-sm-1 waves-effect waves-float waves-light">Save Changes</button>
								<button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
							</div>
						</div>
					</form>
				</div>
			</div>
        </div>
        <!-- Edit Profile Tab ends -->

		<!-- Edit Change Password starts -->
		<div class="tab-pane" id="password" aria-labelledby="changepassword" role="tabpanel">
			<div class="card">
				<h4 class="card-header">Change Password</h4>
				<div class="card-body">
					<form class="form" action="{{route(currentUser().'.changePass')}}" method="post">
					@csrf
					<input type="hidden" name="id" value="{{ encryptor('encrypt', $UserData->id) }}">
					<div class="alert alert-warning mb-2" role="alert">
					<h6 class="alert-heading">Ensure that these requirements are met</h6>
					<div class="alert-body fw-normal">Minimum 5 characters</div>
					</div>
					<div class="row">
						<div class="mb-2 col-md-6 form-password-toggle">
							<label class="form-label" for="newPassword">Old Password</label>
							<div class="input-group input-group-merge form-password-toggle">
							<input class="form-control" type="password" id="oldpass" name="oldpass" value="{{old('oldpass')}}" placeholder="············" required>
							<span class="input-group-text cursor-pointer">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
							</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="mb-2 col-md-6 form-password-toggle">
							<label class="form-label" for="newPassword">New Password</label>
							<div class="input-group input-group-merge form-password-toggle">
							<input class="form-control" type="password" id="pass" name="pass" placeholder="············">
							<span class="input-group-text cursor-pointer">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
							</span>
							</div>
							@if($errors->has('pass'))
							<div class="demo-spacing-0">
								<div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
									<div class="alert-body d-flex align-items-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info me-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
										<span>{{ $errors->first('pass') }}</span>
									</div>
								</div>
							</div>
							@endif
						</div>
						<div class="mb-2 col-md-6 form-password-toggle">
							<label class="form-label" for="confirmPassword">Confirm New Password</label>
							<div class="input-group input-group-merge">
							<input class="form-control" type="password" name="cpass" id="confirmPassword" placeholder="············" requuired>
							<span class="input-group-text cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>
							</div>
							@if($errors->has('cpass'))
							<div class="demo-spacing-0">
								<div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
									<div class="alert-body d-flex align-items-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info me-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
										<span>{{ $errors->first('cpass') }}</span>
									</div>
								</div>
							</div>
							@endif
						</div>
					</div>
					<div>
						<button type="submit" class="btn btn-primary me-2 waves-effect waves-float waves-light">Change Password</button>
					</div>
				</form>
				</div>
			</div>
        <!-- Edit Change Password Tab ends -->
    	</div>
    <!--/ Tab Content -->
  </div>
</section>
@endsection
@push('scripts')
<script>

</script>
@endpush