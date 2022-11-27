@extends('layout.admin.admin_master')
@section('title', 'Edit coupon')
@section('content')

<!--begin::Notice-->
@if( Session::has('response') )
<div class="alert alert-custom alert-{{Session::get('response')['class']}} alert-shadow gutter-b" role="alert">
	<div class="alert-icon">
		<i class="flaticon2-bell-4"></i>
	</div>
	<div class="alert-text">
		{{Session::get('response')['message']}}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

</div>
@endif
<!--end::Notice-->
<div class="card card-custom">
	<div class="card-header flex-wrap border-0 pt-6 pb-0">
		<div class="card-title">
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">User Package</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Edit</a>
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
						<h3 class="card-title">Edit Coupon</h3>
					</div>
					<!--begin::Form-->
					<form class="form" action="{{ route(currentUser().'.updateCoupon') }}" method="POST">
						@csrf
						<input type="hidden" value="{{ Session::get('user') }}" name="userId">
						<input type="hidden" value="{{ encryptor('encrypt', $coupon->id) }}" name="id">
						<div class="card-body">
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Coupon Name</label>
									<input type="text" name="couponName" value="{{ $coupon->name }}"
										class="form-control @if($errors->has('couponName')) {{ 'is-invalid' }} @endif"
										placeholder="Coupon Name" />
									@if($errors->has('couponName'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('couponName') }}
									</small>
									@endif
								</div>
								<div class="col-lg-4">
									<label>Coupon Code</label>
									<input type="text" name="couponCode" value="{{ $coupon->code }}"
										class="form-control @if($errors->has('couponCode')) {{ 'is-invalid' }} @endif"
										placeholder="Coupon Code" />
									@if($errors->has('couponCode'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('couponCode') }}
									</small>
									@endif
								</div>
								<div class="col-lg-4">
									<label>Discount %</label>
									<input type="text" name="discount" value="{{ $coupon->discount }}"
										class="form-control @if($errors->has('discount')) {{ 'is-invalid' }} @endif"
										placeholder="00" />
									@if($errors->has('discount'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('discount') }}
									</small>
									@endif
								</div>
							</div>

							<div class="form-group row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Start Date</label>
										<input type="text" class="form-control date_pick" name="startAt"
											placeholder="DD-MM-YYYY" value="{{ $coupon->startAt }}">
									</div>
								</div>
								<!--end col-->
								<div class="col-md-4">
									<div class="form-group">
										<label>End Date</label>
										<input type="text" class="form-control date_pick" name="endAt"
											placeholder="DD-MM-YYYY" value="{{ $coupon->endAt }}">
									</div>
								</div>
								<!--end col-->
								<div class="col-md-2">
									<div class="form-group">
										<label>Total Coupon</label>
										<input type="text" class="form-control" name="numberOfCoupon" placeholder="00"
											value="{{ $coupon->numberOfCoupon }}">
									</div>
								</div>
								<!--end col-->
								<div class="col-lg-2">
									<label class="control-label">Status</label>
									<select name="status"
										class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
										<option value="1" @if($coupon->status==1) selected @endif>Active</option>
										<option value="0" @if($coupon->status==0) selected @endif>Inactive</option>
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
									<button type="submit" class="btn btn-primary mr-2">Update</button>
									<button type="reset" class="btn btn-secondary">Cancel</button>
								</div>
							</div>
						</div>
						<!--end card-footer-->
					</form>
					<!--end::Form-->
				</div>
				<!--end card-body-->
			</div>
			<!--end card-->
		</div> <!-- end col -->
	</div> <!-- end row -->
</div><!-- container -->
@endsection
@push('scripts')
<script>
	// enable clear button
    $('.date_pick').datepicker({
       format: 'dd-mm-yyyy'
    });
</script>
@endpush