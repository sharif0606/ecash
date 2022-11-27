@extends('layout.admin.admin_master')
@section('title', 'Add New Stock')
@section('content')

<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<div class="card-header">
		<h3 class="card-title">New Appointment</h3>
	</div>
	<!--begin::Form-->
	<form class="form" action="{{ route(currentUser().'.addNewStockM') }}" method="POST">
		@csrf
		<input type="hidden" value="{{ Session::get('user') }}" name="userId">
		<div class="card-body">
			<div class="form-group row">
				<div class="col-lg-4">
					<label>Product Name: <span class="text-danger sup">*</span></label>
					<select name="productId" class="select2 form-control @if($errors->has('productId')) {{ 'is-invalid' }} @endif" style="width: 100%; height:36px;">
					    <option value="">Select Product</option>
						@if(count($allProduct) > 0)
							@foreach($allProduct as $product)
								<option value="{{ $product->id }}-{{ $product->tax }}">{{ $product->brandName }}</option>
							@endforeach
						@endif
					</select>
					@if($errors->has('productId'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('productId') }}
						</small>
					@endif
				</div>
				<div class="col-lg-4">
					<label>BatchId: <span class="text-danger sup">*</span></label>
					<input type="text" name="batchId" value="{{ old('batchId') }}" class="form-control @if($errors->has('batchId')) {{ 'is-invalid' }} @endif" placeholder="Batch ID"/>
					@if($errors->has('batchId'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('batchId') }}
						</small>
					@endif
				</div>
				<div class="col-lg-4">
					<label>Paitent Name: <span class="text-danger sup">*</span></label>
					<input type="text" name="name" value="{{ old('name') }}" class="form-control @if($errors->has('name')) {{ 'is-invalid' }} @endif" placeholder="Paitent Name"/>
					@if($errors->has('name'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('name') }}
						</small>
					@endif
				</div>
				<div class="col-lg-4">
					<label>Age:</label>
					<input type="text" name="age" value="{{ old('age') }}" class="form-control"/>
					@if($errors->has('age'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('age') }}
						</small>
					@endif
				</div>
			</div>
			<div class="form-group row">
			<div class="col-lg-4">
					<label class="control-label">Gender: </label>
					<select name="gender" class="form-control">
						<option value="1" selected>Male</option>
						<option value="0">Female</option>
					</select>
					@if($errors->has('gender'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('gender') }}
						</small>
					@endif
				</div>
			<div class="col-lg-4">
					<label>Contact Number:</label>
					<input type="text" name="contact" value="{{ old('contact') }}" class="form-control" />
					@if($errors->has('contact'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('contact') }}
						</small>
					@endif
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-4">
					<label>Appointment Date:</label>
					<input type="date" name="appDate" value="{{ old('appDate') }}" class="form-control"  />
					@if($errors->has('appDate'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('appDate') }}
						</small>
					@endif
				</div>
				<div class="col-lg-4">
					<label>Appointment Time:</label>
					<input type="time" name="appTime" value="{{ old('appTime') }}" class="form-control"  />
					@if($errors->has('appDate'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('appTime') }}
						</small>
					@endif
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-4">
					<label>Doctor Name:</label>
					<input type="text" name="drName" value="{{ old('drName') }}" class="form-control"  />
					@if($errors->has('drName'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('drName') }}
						</small>
					@endif
				</div>
				<div class="col-lg-4">
					<label>Doctor Department:</label>
					<input type="text" name="dept" value="{{ old('dept') }}" class="form-control"  />
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-12">
					<label>Notes/Problem:</label>
					<textarea class="form-control" rows="5" name="notes">{{ old('address') }}</textarea>
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
					
@endsection
@push('scripts')
<script>
	var avatar1 = new KTImageInput('kt_image_1');
	$('.select2').select2();
</script>
@endpush