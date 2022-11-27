@extends('layout.admin.admin_master')

@section('title', 'Edit District')
@section('content')
<div class="d-flex flex-row flex-column-fluid container">
	<!--begin::Content Wrapper-->
	<div class="main d-flex flex-column flex-row-fluid">
		<!--begin::Subheader-->
		<div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
			<div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
				<!--begin::Info-->
				<div class="d-flex align-items-center flex-wrap mr-1">
					<!--begin::Page Heading-->
					<div class="d-flex align-items-baseline mr-5">
						<!--begin::Page Title-->
						<h5 class="text-dark font-weight-bold my-2 mr-5">Edit District</h5>
						<!--end::Page Title-->
						<!--begin::Breadcrumb-->
						<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
							<li class="breadcrumb-item">
								<a href="" class="text-muted">Home</a>
							</li>
							<li class="breadcrumb-item">
								<a href="" class="text-muted">District</a>
							</li>
							<li class="breadcrumb-item">
								<a href="" class="text-muted">Edit District</a>
							</li>
						</ul>
						<!--end::Breadcrumb-->
					</div>
					<!--end::Page Heading-->
				</div>
				<!--end::Info-->
			</div>
		</div>
		<!--end::Subheader-->
		<div class="content flex-column-fluid" id="kt_content">
			<div class="row">
				<div class="col-lg-12">
					<!--begin::Card-->
					<div class="card card-custom gutter-b example example-compact">
						<div class="card-header">
							<h3 class="card-title">Edit Zone</h3>
						</div>
						<!--begin::Form-->
						<form class="form" action="{{ route(currentUser().'.updateZone') }}" method="POST">
							@csrf
							<input type="hidden" name="id" value="{{ encryptor('encrypt', $zone->id) }}">
							<!--begin::Card Body-->
    						<div class="card-body">
    							<div class="form-group row">
    								<div class="col-lg-3">
    									<label class="control-label">Division Id:</label>
    									<select class="form-control datatable-input" name="stateId" data-col-index="2">
    										<option value="">Select Division</option>
    										@if(count($allState) > 0)
    											@foreach($allState as $state)
    												<option value="{{ $state->id }}" @if($zone->stateId== $state->id) selected @endif>{{ $state->name }}</option>
    											@endforeach
    										@endif
    									</select>
    								</div>
    								<div class="col-lg-3">
    									<label class="control-label">District code</label>
    			                        <input type="text" name="zoneCode" value="{{ $zone->code }}" class="form-control @if($errors->has('zoneCode')) {{ 'is-invalid' }} @endif" placeholder="District Code" />
    									@if($errors->has('zoneCode'))
    										<small class="d-block text-danger mb-3">
    											{{ $errors->first('zoneCode') }}
    										</small>
    									@endif
    								</div>
    								<div class="col-lg-6">
    									<label class="control-label">District Name</label>
    			                        <input type="text" name="zoneName" value="{{ $zone->name }}" class="form-control @if($errors->has('zoneName')) {{ 'is-invalid' }} @endif" placeholder="District Name" />
    									@if($errors->has('zoneName'))
    										<small class="d-block text-danger mb-3">
    											{{ $errors->first('zoneName') }}
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
    									<button type="submit" class="btn btn-primary mr-2">Update</button>
    									<button type="reset" class="btn btn-secondary">Cancel</button>
    								</div>
    							</div>
    						</div>
                            <!--end card-footer-->
						</form> <!--end::Form-->
					</div> <!--end::Card-->
				</div>
			</div>
		</div> <!--end::Content-->
	</div> <!--begin::Content Wrapper-->
</div>
@endsection