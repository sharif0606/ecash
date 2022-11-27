@extends('layout.admin.admin_master')
@section('title', 'Edit brand')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Brand Edit</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Brand</a></li>
				<li class="breadcrumb-item active">Edit</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="content-header-right col-md-3 col-6 mb-2">
	  </div>
    </div>
	<!--begin::Notice-->
	@if( Session::has('response') )
	<div class="alert alert-{{Session::get('response')['class']}} alert-dismissible fade show" role="alert">
		<div class="alert-body">
			{{Session::get('response')['message']}}
		</div>
		<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif
	<!--end::Notice-->

	<!--begin::Card-->
	<div class="card">
		<!--begin::Form-->
		<form class="form" action="{{ route(currentUser().'.updateBrand') }}" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" value="{{ Session::get('user') }}" name="userId"/>
			<input type="hidden" name="id" value="{{ encryptor('encrypt', $brand->id) }}"/>
			<div class="card-body">
				<div class="form-group row">
					<div class="col-lg-3">
						<label >Brand Logo</label>
						<input type="file" class="form-control" name="brandLogo" accept=".png, .jpg, .jpeg"/>
						@if($errors->has('brandLogo'))
							<small class="d-block text-danger mb-3">
								{{ $errors->first('brandLogo') }}
							</small>
						@endif
					</div>
					<div class="col-lg-6">
						<label>Brand Name</label>
						<input type="text" name="brandName" value="{{ $brand->name }}"
							class="form-control @if($errors->has('brandName')) {{ 'is-invalid' }} @endif"/>
						@if($errors->has('brandName'))
							<small class="d-block text-danger mb-3">
								{{ $errors->first('brandName') }}
							</small>
						@endif
					</div>
					<div class="col-lg-3">
						<label class="control-label">Status</label>
						<select name="status"
							class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
							<option value="1" selected>Active</option>
							<option value="0">Inactive</option>
						</select>
						@if($errors->has('status'))
							<small class="d-block text-danger mb-3">
								{{ $errors->first('status') }}
							</small>
						@endif
					</div>
				</div>
			</div>
			<!--end card-body-->
			<div class="card-footer">
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col-lg-8">
						<button type="submit" class="btn btn-primary mr-2">Submit</button>
						<button type="reset" class="btn btn-secondary">Cancel</button>
					</div>
				</div>
			</div><!--end card-footer-->
		</form>
	</div>
</div>

@endsection
@push('scripts')
<script>
	var avatar1 = new KTImageInput('kt_image_1');
</script>
@endpush