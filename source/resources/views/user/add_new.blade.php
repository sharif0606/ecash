@extends('layout.admin.admin_master')
@push('styles')
<style>
/*# sourceMappingURL=app.css.map */
.image-upload .thumb .profilePicPreview {
    width: 100%;
    height: 150px;
    display: block;
    border: 3px solid #f1f1f1;
    box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
    border-radius: 10px;
    background-size: cover !important;
    background-position: top;
    background-repeat: no-repeat;
    position: relative;
    overflow: hidden;
}

.image-upload .thumb .profilePicPreview.logoPicPrev {
    background-size: contain !important;
    background-position: center;
}

.image-upload .thumb .profilePicUpload {
    font-size: 0;
    opacity: 0;
}

.image-upload .thumb .avatar-edit label {
    text-align: center;
    line-height: 45px;
    font-size: 18px;
    cursor: pointer;
    padding: 2px 25px;
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.16);
    transition: all 0.3s;
}

.image-upload .thumb .avatar-edit label:hover {
    transform: translateY(-3px);
}

.image-upload .thumb .profilePicPreview .remove-image {
    position: absolute;
    top: -9px;
    right: -9px;
    text-align: center;
    width: 55px;
    height: 55px;
    font-size: 24px;
    border-radius: 50%;
    background-color: #df1c1c;
    color: #fff;
    display: none;
}

.image-upload .thumb .profilePicPreview.has-image .remove-image {
    display: block;
	border: none;
	cursor: pointer;
}
.bg--success {
    background-color: #28c76f !important;
}
</style>
@endpush
@section('title', 'Add New User')
@section('content')

<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Dashboard</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a></li>
				<li class="breadcrumb-item"><a href="@if(currentUser() === 'marketingmanager' || currentUser() === 'owner' || currentUser() === 'salesmanager' || currentUser() === 'superadmin' || currentUser() === 'executive') {{route(currentUser().'.allUser')}} @endif">User</a></li>
				<li class="breadcrumb-item active">Add</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Edit User</h4>
				</div>
				<div class="card-body">
					<form class="form" action="{{ route(currentUser().'.addNewUser') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<input type="hidden" value="{{Session::get('user')}}" name="userId">
					<div class="col-md-3">
						<div class="form-group">
							<div class="image-upload">
								<div class="thumb">
									<div class="avatar-preview">
										<div class="profilePicPreview" style="background-image: url()">
											<button type="button" class="remove-image">x</button>
										</div>
									</div>
									<div class="avatar-edit">
										<input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
										<label for="profilePicUpload1" class="text-white bg--success">@lang('Upload Image')</label>
										<small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg').</b> @lang('Image will be resized into 400x400px') </small>
									</div>
								</div>
							</div>
						</div>

					</div>
						<div class="form-group row">
							<div class="col-lg-4">
								<label>User Role: <span class="text-danger sup">*</span></label>
								<select name="role"
									class="form-control select2 @if($errors->has('role')) {{ 'is-invalid' }} @endif">
									@if(count($roles) > 0)
									@foreach($roles as $role)
									<option value="{{ $role->id }}">{{ $role->type }}</option>
									@endforeach
									@endif
								</select>
								@if($errors->has('role'))
								<small class="d-block text-danger mb-3">
									{{ $errors->first('role') }}
								</small>
								@endif
							</div>
							
							<div class="col-lg-4">
								<label>Full Name: <span class="text-danger sup">*</span></label>
								<input type="text" name="fullName" value="{{ old('name') }}"
									class="form-control @if($errors->has('fullName')) {{ 'is-invalid' }} @endif"
									placeholder="Full Name" />
								@if($errors->has('fullName'))
								<small class="d-block text-danger mb-3">
									{{ $errors->first('fullName') }}
								</small>
								@endif
							</div>
							@if(currentUser() == 'owner')
							<div class="col-lg-4">
								<label>Branch: <span class="text-danger sup">*</span></label>
								<select name="branchId"
									class="form-control select2" required>
									@if(count($allBranch) > 0)
									@foreach($allBranch as $branch)
									<option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							@endif
						</div>
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Email: <span class="text-danger sup">*</span></label>
								<div class="input-group">
									<span class="input-group-text">
										<i data-feather='mail'></i>
									</span>
									<input type="email" name="email" value="{{  old('email') }}" class="form-control"/>
								</div>
							</div>
							<div class="col-lg-4">
								<label>Mobile Number: <span class="text-danger sup">*</span></label>
								<div class="input-group">
									<span class="input-group-text">
										<i data-feather='phone'></i>
									</span>
									<input name="mobileNumber" type="text" value="{{  old('mobileNumber') }}"
										class="form-control  @if($errors->has('email')) {{ 'is-invalid' }} @endif"
										placeholder="Enter only digits" />
								</div>
								@if($errors->has('mobileNumber'))
								<small class="d-block text-danger mb-3">
									{{ $errors->first('mobileNumber') }}
								</small>
								@endif
							</div>
							<div class="col-lg-4">
								<label>NID:</label>
								<input type="text" name="nid" value="{{  old('details->nid') }}" class="form-control"
									placeholder="NID Number" />
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-4">
								<label>Password <span class="text-danger sup">*</span></label>
								<div>
									<input type="password" name="password"
										class="form-control @if($errors->has('password')) {{ 'is-invalid' }} @endif"
										placeholder="******" />
								</div>
								@if($errors->has('password'))
								<small class="d-block text-danger mb-3">
									{{ $errors->first('password') }}
								</small>
								@endif
							</div>
							<!--end form-group-->
							<div class="col-md-4">
								<label>Confirm Password</label>
								<div>
									<input type="password" name="password_confirmation" class="form-control"
										placeholder="******" />
								</div>
							</div>
						
							<div class="col-lg-4">
								<label class="control-label">Status: </label>
								<select name="status"
									class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
									<option value="1" >Active</option>
									<option value="0" >Inactive</option>
								</select>
								@if($errors->has('status'))
								<small class="d-block text-danger mb-3">
									{{ $errors->first('status') }}
								</small>
								@endif
							</div>
							<div class="col-lg-4">
								<label>Division:</label>
								<select name="state_id" onchange="get_district(this.value)" class="form-control"
									required>
									<option vlaue="">Select Division</option>
									@if(count($allState) > 0)
									@foreach($allState as $state)
									<option value="{{ $state->id }}">{{ $state->code.'-'. $state->name}}</option>
									@endforeach
									@endif
								</select>
								@if($errors->has('state_id'))
								<small class="d-block text-danger mb-3">
									Division is required
								</small>
								@endif
							</div>
							<div class="col-lg-4">
								<label>District:</label>
								<select name="zone_id" class="form-control" required>
									<option vlaue="">Select District</option>
									@if(count($allZone) > 0)
									@foreach($allZone as $zone)
									<option value="{{ $zone->id }}"
										class="dist dist{{$zone->stateId}}">{{ $zone->code.'-'. $zone->name}}
									</option>
									@endforeach
									@endif
								</select>
								@if($errors->has('zone_id'))
								<small class="d-block text-danger mb-3">
									District is required
								</small>
								@endif
							</div>
						</div>
	
						<div class="row mt-2">
							<div class="col-lg-12">
								<button type="submit" class="btn btn-primary mr-2">Submit</button>
								<button type="reset" class="btn btn-secondary">Cancel</button>
							</div>
						</div>
				</form>
				<!--end::Form-->
				</div>
			</div>
		</div>
	</div>

	
</div>
@endsection
@push('scripts')
<script>
function proPicURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        var preview = $(input).parents('.thumb').find('.profilePicPreview');
        $(preview).css('background-image', 'url(' + e.target.result + ')');
        $(preview).addClass('has-image');
        $(preview).hide();
        $(preview).fadeIn(650);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  
  $(".profilePicUpload").on('change', function () {
    proPicURL(this);
  });
  
  $(".remove-image").on('click', function () {
    $(this).parents(".profilePicPreview").css('background-image', 'none');
    $(this).parents(".profilePicPreview").removeClass('has-image');
    $(this).parents(".thumb").find('input[type=file]').val('');
  });
  

  
</script>
@endpush