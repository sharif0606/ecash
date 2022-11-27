@extends('layout.admin.admin_master')
@section('title', 'Add new package')
@section('content')
<div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Package Add</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Package</a></li>
				<li class="breadcrumb-item active">Add New</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="content-header-right col-md-3 col-6 mb-2"></div>
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

	<div class="card">
		<!--begin::Form-->
		<form class="form" action="{{ route(currentUser().'.addNewPackage') }}" method="POST">
			@csrf
			<input type="hidden" value="{{ Session::get('user') }}" name="userId">
            <div class="card-body">
				<div class="form-group row">
					<div class="col-lg-4">
						<label>Package Name  <small class="text-danger font-13">*</small></label>
                        <input type="text" name="packageName" value="{{ old('packageName') }}" class="form-control @if($errors->has('packageName')) {{ 'is-invalid' }} @endif" placeholder="Package Name" />
                        @if($errors->has('packageName'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('packageName') }}
                            </small>
                        @endif
                    </div>
					<div class="col-lg-4">
						<label>Package Code  <small class="text-danger font-13">*</small></label>
                        <input type="text" name="packageCode" value="{{ old('packageCode') }}" class="form-control @if($errors->has('')) {{ 'is-invalid' }} @endif" placeholder="Package Code" />
                        @if($errors->has('packageCode'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('packageCode') }}
                            </small>
                        @endif
                    </div>
					<div class="col-lg-4">
						<label>Price</label>
                        <input type="text" name="price" value="{{ old('price') }}" class="form-control" placeholder="00" />
                    </div>
                </div>
				
				<div class="form-group row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Number of Invoice <small class="text-danger font-13">*</small></label>
							<input type="text" class="form-control @if($errors->has('duration')) {{ 'is-invalid' }} @endif" name="duration" placeholder="00" value="{{ old('duration') }}">
							@if($errors->has('duration'))
								<small class="d-block text-danger mb-3">
									{{ $errors->first('duration') }}
								</small>
							@endif
						</div>
					</div><!--end col-->
					<div class="col-lg-4">
						<label class="control-label">A user can buy? (number only)</label>
						<input type="number" name="canbuy" value="{{ old('canbuy') }}" class="form-control" placeholder="00" />
					</div>
					<div class="col-lg-4">
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
			</div>
            <!--end card-footer-->
        </form><!--end::Form-->
	</div><!--end card-->
</div><!--end container-->
@endsection