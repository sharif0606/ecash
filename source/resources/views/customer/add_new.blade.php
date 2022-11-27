@extends('layout.admin.admin_master')
@section('title', 'Add New Customer')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Customer Add</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Customer</a></li>
				<li class="breadcrumb-item active">Add New</li>
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
	<div class="card ">
		<!--begin::Form-->
		<form class="form" action="{{ route(currentUser().'.addNewCustomer') }}" method="POST">
			@csrf
			<input type="hidden" value="{{ Session::get('user') }}" name="userId">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-lg-8 mb-1">
						<label>Customer Name: <span class="text-danger sup">*</span></label>
						<input type="text" name="name" value="{{ old('name') }}"
						class="form-control @if($errors->has('name')) {{ 'is-invalid' }} @endif"
							placeholder="Customer Name" />
						@if($errors->has('name'))
							<small class="d-block text-danger">
								{{ $errors->first('name') }}
							</small>
						@endif
					</div>
					<div class="col-lg-4 mb-1">
						<label>Customer Mobile No: <span class="text-danger sup">*</span></label>
						<input type="text" name="custCode" value="{{ old('custCode') }}"
						class="form-control @if($errors->has('custCode')) {{ 'is-invalid' }} @endif" placeholder="Customer Mobile No" />
						@if($errors->has('custCode'))
							<small class="d-block text-danger">
								{{ $errors->first('custCode') }}
							</small>
						@endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-12 mb-1">
						<label>Address:</label>
						<textarea name="address" class="form-control">{{ old('address') }}</textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4 mb-1">
						<label>Care of/Contact Person:</label>
						<select name="contact_person" class="form-control">
							@if($allCustomer)
								@foreach($allCustomer as $c)
									<option value="{{ $c->id }}" @if(old('contact_person') == $c->id) selected @endif >{{ $c->name.'-'. $c->custCode}}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="col-lg-4 mb-1">
						<label>Alternate Contact Number:</label>
						<input type="text" name="contact_no_b" value="{{ old('contact_no_b') }}" class="form-control" />
					</div>
					<div class="col-lg-4 mb-1">
						<label>Email: </label>
						<input type="email" name="email" value="{{ old('email') }}" class="form-control" />
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4 mb-1">
						<label>District:</label>
						<select name="state_id" onchange="$('.zonop').hide(); $('.zonop'+this.value).show()" class="form-control">
							@if(count($allState) > 0)
								@foreach($allState as $state)
									<option value="{{ $state->id }}" @if(Session::get('state_id') == $state->id) selected @endif >{{ $state->code.'-'. $state->name}}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="col-lg-4 mb-1">
						<label>City:</label>
						<select name="zone_id" class="form-control">
							@if(count($allZone) > 0)
								@foreach($allZone as $zone)
									<option value="{{ $zone->id }}" class="zonop zonop{{$zone->stateId}}" @if(Session::get('zone_id') == $zone->id) selected @endif >{{ $zone->code.'-'. $zone->name}}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="col-lg-4 mb-1">
						<label class="control-label">Status: </label>
						<select name="status" class="form-control">
							<option value="1" selected>Active</option>
							<option value="0">Inactive</option>
						</select>
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
	</div>
	<!--end::Card-->
</div>
@endsection
@push('scripts')
<script>
	$('.select2').select2();
</script>
@endpush