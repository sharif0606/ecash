@extends('layout.admin.admin_master')
@section('title', 'Edit Appointment')
@section('content')

<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<div class="card-header">
		<h3 class="card-title">Edit Appointment</h3>
	</div>
    <!--begin::Form-->
    <form class="form" action="{{ route(currentUser().'.updateAppointment') }}" method="POST">
    @csrf
    <input type="hidden" value="{{Session::get('user')}}" name="userId">
    <input type="hidden" value="{{encryptor('encrypt', $data->id)}}" name="id">
    <div class="card-body">
    	<div class="form-group row">
    		<div class="col-lg-4">
    			<label>Attendee Name: <span class="text-danger sup">*</span></label>
    			<input type="text" name="attName" value="{{ $data->attName }}"
    			class="form-control @if($errors->has('attName')) {{ 'is-invalid' }} @endif" placeholder="Attendance Name" required/>
    			@if($errors->has('attName'))
    				<small class="d-block text-danger mb-3">
    					{{ $errors->first('attName') }}
    				</small>
    			@endif
    		</div>
    		<div class="col-lg-4">
    			<label>Paitent Name: <span class="text-danger sup">*</span></label>
    			<input type="text" name="name" value="{{ $data->name }}"
    			class="form-control @if($errors->has('name')) {{ 'is-invalid' }} @endif" placeholder="Paitent Name" required/>
    			@if($errors->has('name'))
    				<small class="d-block text-danger mb-3">
    					{{ $errors->first('name') }}
    				</small>
    			@endif
    		</div>
    		<div class="col-lg-4">
    			<label>Age:</label>
    			<input type="text" name="age" value="{{ $data->age }}" class="form-control" required/>
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
    			<select name="gender" class="form-control" required>
    				<option value="1"  @if($data->type == 1) selected @endif >Male</option>
    				<option value="0"  @if($data->type == 0) selected @endif >Female</option>
    			</select>
				@if($errors->has('gender'))
					<small class="d-block text-danger mb-3">
						{{ $errors->first('gender') }}
					</small>
				@endif
    		</div>
			<div class="col-lg-4">
    			<label>Contact Number:</label>
    			<input type="text" name="contact" value="{{ $data->contact }}" class="form-control" />
				@if($errors->has('contact'))
					<small class="d-block text-danger mb-3">
						{{ $errors->first('contact') }}
					</small>
				@endif
    		</div>
		</div>
    	<div class="form-group row">
    		<div class="col-lg-4">
    			<label>Appintment Date:</label>
    			<input type="date" name="appDate" value="{{ $data->appDate }}" class="form-control" />
				@if($errors->has('appDate'))
					<small class="d-block text-danger mb-3">
						{{ $errors->first('appDate') }}
					</small>
				@endif
    		</div>
			<div class="col-lg-4">
				<label>Appointment Time:</label>
				<input type="time" name="appTime" value="{{ $data->appTime }}" class="form-control"  />
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
					<input type="text" name="drName" value="{{ $data->drName }}" class="form-control"  />
					@if($errors->has('drName'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('drName') }}
						</small>
					@endif
				</div>
				<div class="col-lg-4">
					<label>Doctor Department:</label>
					<input type="text" name="dept" value="{{ $data->dept }}" class="form-control"  />
				</div>
			</div>
    	<div class="form-group row">
    		<div class="col-lg-12">
    			<label>Notes/Problem:</label>
    			<textarea class="form-control" rows="5" name="notes">{{ $data->notes }}</textarea>
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