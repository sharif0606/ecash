@extends('layout.admin.admin_master')

@section('title', 'Add new Division')
@section('content')
<div class="card card-custom">
	<div class="card-header flex-wrap border-0 pt-6 pb-0">
		<div class="card-title">
			<h3 class="card-label">Add Division
			<span class="d-block text-muted pt-2 font-size-sm"></span></h3>
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Division List</a>
				</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
	</div>
<!--end::Subheader-->
	<div class="content flex-column-fluid" id="kt_content">
		<div class="row">
			<div class="col-lg-12">
				<!--begin::Card-->
				<div class="card card-custom gutter-b example example-compact">
					<div class="card-header">
						<h3 class="card-title">New Division</h3>
					</div>
					<!--begin::Form-->
					<form class="form" action="{{ route(currentUser().'.addNewState') }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                    <div class="card-body">
						<div class="form-group row">
						<div class="col-lg-6">
								<label class="control-label">Division Code</label>
								<input type="text" name="stateCode" value="{{ old('stateCode') }}" class="form-control @if($errors->has('stateCode')) {{ 'is-invalid' }} @endif" placeholder="Division Code" />
								@if($errors->has('stateCode'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('stateCode') }}
									</small>
								@endif
							</div>
							<div class="col-lg-6">
								<label class="control-label">Division Name</label>
								<input type="text" name="stateName" value="{{ old('stateName') }}" class="form-control @if($errors->has('stateName')) {{ 'is-invalid' }} @endif" placeholder="Division Name" />
								@if($errors->has('stateName'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('stateName') }}
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
					</form>
					<!--end::Form-->
				</div><!--end card-body-->
			</div><!--end card-->
		</div> <!-- end col -->
	</div> <!-- end row -->
</div><!-- container -->
@endsection
