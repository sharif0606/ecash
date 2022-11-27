@extends('layout.admin.admin_master')
@section('title', 'Add new coupon')
@push('styles')

<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@section('content')
<div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Coupon Add</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Coupon</a></li>
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
		<form class="form" action="{{ route(currentUser().'.addNewCoupon') }}" method="POST">
			@csrf
			<input type="hidden" value="{{ Session::get('user') }}" name="userId">
            <div class="card-body">
				<div class="form-group row">
					<div class="col-lg-3">
						<label>Coupon Name</label>
                        <input type="text" name="couponName" value="{{ old('couponName') }}" class="form-control @if($errors->has('couponName')) {{ 'is-invalid' }} @endif" placeholder="Coupon Name" />
                        @if($errors->has('couponName'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('couponName') }}
                            </small>
                        @endif
                    </div>
					<div class="col-lg-3">
						<label>Coupon Code</label>
                        <input type="text" name="couponCode" value="{{ old('couponCode') }}" class="form-control @if($errors->has('couponCode')) {{ 'is-invalid' }} @endif" placeholder="Coupon Code" />
                        @if($errors->has('couponCode'))
                            <small class="d-block text-danger mb-3">
                                {{ $errors->first('couponCode') }}
                            </small>
                        @endif
                    </div>
					<div class="col-lg-2">
						<label>Discount %</label>
                        <input type="text" name="discount" value="{{ old('discount') }}" class="form-control" placeholder="00" />
                    </div>
					<div class="col-lg-4">
						<label>Discount (Number of Invoice)</label>
                        <input type="text" name="noofbill" value="{{ old('noofbill') }}" class="form-control" placeholder="00" />
                    </div>
                </div>
				
				<div class="form-group row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Start Date</label>
							<input type="text" class="form-control date_pick" value="{{ old('startAt') }}" name="startAt">
						</div>
					</div><!--end col-->
					<div class="col-md-4">
						<div class="form-group">
							<label>End Date</label>
							<input type="text" class="form-control date_pick" value="{{ old('endAt') }}" name="endAt">
						</div>
					</div><!--end col-->
					<div class="col-md-2">
						<div class="form-group">
							<label>Total Coupon</label>
							<input type="text" class="form-control" name="numberOfCoupon" placeholder="00" value="{{ old('numberOfCoupon') }}">
						</div>
					</div><!--end col-->
					<div class="col-lg-2">
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
	</div> <!-- end row -->
</div><!-- container -->
@endsection
@push('scripts')
<script src="{{asset('/')}}assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<script>
// enable clear button
date = new Date(),
	  datepicker = $('.date-picker'),
    $('.date_pick').flatpickr({
       defaultDate: date
    });
</script>
@endpush