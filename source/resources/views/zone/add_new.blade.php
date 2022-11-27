@extends('layout.admin.admin_master')

@section('title', 'Add new District')
@section('content')
<div class="card card-custom">
	<div class="card-header flex-wrap border-0 pt-6 pb-0">
		<div class="card-title">
			<h3 class="card-label">Add District <span class="d-block text-muted pt-2 font-size-sm"></span></h3>
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Home</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">District List</a>
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
						<h3 class="card-title">New District</h3>
					</div>
					<!--begin::Form-->
					<form class="form" action="{{ route(currentUser().'.addNewZone') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ Session::get('user') }}" name="userId"> 
                        <div class="card-body">
    						<div class="form-group row">
    							<div class="col-lg-3">
    								<label class="control-label">DivisionId:</label>
    								<select class="form-control datatable-input" name="stateId" data-col-index="2">
    								    <option value="">Select Division</option>
    									@if(count($allState) > 0)
    										@foreach($allState as $state)
    										    <option value="{{ $state->id }}">{{ $state->name }}</option>
    										@endforeach
    									@endif
    								</select>
    							</div>
    							<div class="col-lg-3">
    								<label class="control-label">District Code</label>
    	                            <input type="text" name="zoneCode" value="{{ old('zoneCode') }}" class="form-control @if($errors->has('zoneCode')) {{ 'is-invalid' }} @endif" placeholder="District Code" />
    								@if($errors->has('zoneCode'))
    									<small class="d-block text-danger mb-3">
    										{{ $errors->first('zoneCode') }}
    									</small>
    								@endif
    							</div>
    							<div class="col-lg-6">
    								<label class="control-label">District Name</label>
    								<input type="text" name="zoneName" value="{{ old('zoneName') }}" class="form-control @if($errors->has('zoneName')) {{ 'is-invalid' }} @endif" placeholder="District Name" />
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