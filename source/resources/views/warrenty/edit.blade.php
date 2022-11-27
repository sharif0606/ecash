@extends('layout.admin.admin_master')
@section('title', 'Edit Warrenty')
@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/plugins/forms/pickers/form-flat-pickr.css">
@endpush
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Warrenty Edit</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Warrenty</a></li>
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

	<div class="card">
	<!--begin::Form-->
	 <form class="form" action="{{ route(currentUser().'.updateWarrenty') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" value="{{encryptor('encrypt', $data->id)}}" name="id">
		<div class="card-body">
            <div class="row form-group">
				<div class="col-md-6 mb-1">
					<h6 class="invoice-to-title">Warrenty Invoice:</h6>
					<div class="invoice-customer">
						<select class="bill_id form-select"  name="bill_id" required>
							<option></option>
							@if(count($allsellItems) > 0)
								@foreach($allsellItems as $sItems)
									<option value="{{ $sItems->id }}" @if($sItems->id==$data->bill_id) selected @endif>{{ $company->shopCode }}-{{date('y')}}-{{ str_pad($sItems->bill_no,8,'0',STR_PAD_LEFT) }}</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<input type="hidden" name="item_id" value="{{$data->item_id}}"/>
				<div class="col-md-6 mb-1">
					<label> Received Date: <span class="text-danger sup">*</span></label>
					<input type="text" name="receive_date" class="form-control invoice-edit-input date-picker" value="{{$data->receive_date}}"/>
				</div>
            </div>
			


		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-lg-4"></div>
				<div class="col-lg-8">
					<button type="submit" class="btn btn-primary mr-2">Submit</button>
					<button type="reset" class="btn btn-secondary">Cancel</button>
				</div>
			</div>
		</div>
	</form>
	<!--end::Form-->
</div>
<!--end::Card-->
@endsection
@push('scripts')
<script src="{{asset('/')}}assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{asset('/')}}assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<script>
	// init date picker
	var newdate = '{!! $data->receive_date !!}';
	date = new Date();
	datepicker = $('.date-picker');
	$('.bill_id').select2({
    	disabled: true
	});
	if (datepicker.length) {
	  datepicker.each(function () {
		$(this).flatpickr({
		  defaultDate: date
		});
	  });
	}
</script>
@endpush