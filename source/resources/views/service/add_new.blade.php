@extends('layout.admin.admin_master')
@section('title', 'Add New Service')
@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/components.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/plugins/forms/pickers/form-flat-pickr.css">
@endpush
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Service Add</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Service</a></li>
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

	<div class="card">
	<!--begin::Form-->
	 <form class="form" action="{{ route(currentUser().'.addNewService') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" value="{{Session::get('user')}}" name="userId">
		<div class="card-body">
			<div class="form-group row row-bill-to">
				<div class="col-md-12 mb-1">
					<label> Product Details: <span class="text-danger sup">*</span></label>
					<textarea class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="Product Details" name="product_detl" required></textarea>
				</div>
				<div class="col-md-4 mb-1">
					<label> IMEI: <span class="text-danger sup">*</span></label>
					<input type="text" name="imei" class="form-control" />
				</div>
				<div class="col-md-4 mb-1">
					<label> Received Date: <span class="text-danger sup">*</span></label>
					<input type="text" name="receive_date" class="form-control invoice-edit-input date-picker" required/>
				</div>
				<div class="invoice-padding col-xl-4 mb-lg-1 ps-0">
					<h6 class="invoice-to-title">Select Customer:</h6>
					<div class="invoice-customers">
						<select class="invoiceto form-select" id="addcustomer_id" name="customer_id" required>
							<option></option>
							@if($allCustomer)
								@foreach ($allCustomer as $cust)
									<option value="{{$cust['id']}}">{{$cust['custCode']}} - {{$cust['name']}}</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="invoice-padding col-xl-4 p-0 ps-xl-2 mt-xl-0 mt-2 col-bill-to">
					<h6 class="mb-2">Customer Details:</h6>
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
	<!-- Add New Customer Sidebar -->
	<div class="modal modal-slide-in fade" id="add-new-customer-sidebar" aria-hidden="true">
		<div class="modal-dialog sidebar-lg">
			<div class="modal-content p-0">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				<div class="modal-header mb-1">
					<h5 class="modal-title">
						<span class="align-middle">Add Customer</span>
					</h5>
				</div>
				<div class="modal-body flex-grow-1">
					<form id="customerForm">
						<div class="mb-1">
							<label for="customer-name" class="form-label">Customer Name  <span class="text-danger sup">*</span></label>
							<input type="text" name="name" id="cname" class="form-control"/>
							<small class="d-none text-danger mb-3 cname">
								Customer Name is required
							</small>
						</div>
						<div class="mb-1">
							<label for="custCode" class="form-label">Phone No. 1  <span class="text-danger sup">*</span></label>
							<input type="text" class="form-control" id="custCode" name="custCode"/>
							<small class="d-none text-danger mb-3 custCode">
								Customer Mobile No is required
							</small>
						</div>
						<div class="mb-1">
							<label for="contact_no_b" class="form-label">Phone No. 2</label>
							<input type="text" class="form-control" id="contact_no_b" name="contact_no_b"/>
						</div>
						<div class="mb-1">
							<label for="email" class="form-label">Email</label>
							<input type="email" class="form-control" id="email" name="email"/>
						</div>
						<div class="mb-1">
							<label for="address" class="form-label">Customer Address</label>
							<textarea class="form-control" id="address" name="address" cols="2" rows="2"></textarea>
						</div>
						<div class="mb-1 position-relative">
							<label for="state_id" class="form-label">District</label>
							<select class="form-select" id="state_id" name="state_id">
								@if(count($allState) > 0)
									@foreach($allState as $state)
										<option value="{{ $state->id }}" @if(Session::get('state_id') == $state->id) selected @endif>{{ $state->code.'-'. $state->name}}</option>
									@endforeach
								@endif
							</select>
						</div>
						<div class="mb-1 position-relative">
							<label for="zone_id" class="form-label">City</label>
							<select class="form-select" id="zone_id" name="zone_id">
								@if(count($allZone) > 0)
									@foreach($allZone as $zone)
										<option value="{{ $zone->id }}" @if(Session::get('zone_id') == $zone->id) selected @endif >{{ $zone->code.'-'. $zone->name}}</option>
									@endforeach
								@endif
							</select>
						</div>
						<div class="mb-1 d-flex flex-wrap mt-2">
							<button type="button" class="btn btn-primary me-1" onclick="saveCustomer()">Add</button>
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Add New Customer Sidebar -->
@push('scripts')
<script src="{{asset('/')}}assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{asset('/')}}assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<script>
	// init date picker
	date = new Date();
	datepicker = $('.date-picker');
	
	if (datepicker.length) {
	  datepicker.each(function () {
		$(this).flatpickr({
		  defaultDate: date
		});
	  });
	}


  
	$(function () {
	'use strict';
	// init date picker

  	var data_list={
	  @if($allCustomer)
	  	@foreach ($allCustomer as $cust)
		  {{$cust->id}}:{
			name: '{{$cust->name}}',
			address: '{{$cust->address}}',
			contact_person: '{{$cust->contact_person}}',
			contact_no_b: '{{$cust->contact_no_b}}',
			custCode: '{{$cust->custCode}}',
			email: '{{$cust->email}}',
			zone: @if($cust->zone)"{{$cust->zone->name}}" @else " " @endif},
		 @endforeach
	  @endif
	};
	var select2 = $('.invoiceto'),
	customerDetails = data_list;
	// Select2
	if (select2.length) {
		select2.select2({
		placeholder: 'Select Customer',
		dropdownParent: $('.invoice-customers')
	});


	select2.on('change', function () {
		var $this = $(this),
		  renderDetails =
			'<div class="customer-details mt-1">' +
			'<p class="mb-25">' +
			customerDetails[$this.val()].name +
			'</p>' +
			'<p class="mb-25">' +
			customerDetails[$this.val()].address +
			'</p>' +
			'<p class="mb-25">' +
			customerDetails[$this.val()].zone +
			'</p>' +
			'<p class="mb-0">' +
			customerDetails[$this.val()].contact_no_b +
			'</p>' +
			'<p class="mb-0">' +
			customerDetails[$this.val()].custCode +
			'</p>' +
			'<p class="mb-0">' +
			customerDetails[$this.val()].email +
			'</p>' +
			'</div>';
		$('.row-bill-to').find('.customer-details').remove();
		$('.row-bill-to').find('.col-bill-to').append(renderDetails);
	});
  
	select2.on('select2:open', function () {
		if (!$(document).find('.add-new-customer').length) {
		  $(document)
			.find('.select2-results__options')
			.before(
			  '<div class="add-new-customer btn btn-flat-success cursor-pointer rounded-0 text-start mb-50 p-50 w-100" data-bs-toggle="modal" data-bs-target="#add-new-customer-sidebar">' +
				feather.icons['plus'].toSvg({ class: 'font-medium-1 me-50' }) +
				'<span class="align-middle">Add New Customer</span></div>'
			);
		}
	  });
	}

	// Close select2 on modal open
	$(document).on('click', '.add-new-customer', function () {
	  select2.select2('close');
	});


});
function saveCustomer(){
	if(!$('#custCode').val()){
		$('.custCode').addClass('d-block');
		$('.custCode').removeClass('d-none');
		return;
	}
	if(!$('#cname').val()){
		$('.custCode').addClass('d-none');
		$('.cname').removeClass('d-none');
		$('.cname').addClass('d-block');
		return;
	}
	var data=$('#customerForm').serialize();
	$('.waiting').show();
	$('.saving').hide();
	$.ajax({
        type: "GET",
        url: "{{route(currentUser().'.saveCustomer')}}?"+data,
        data: {  },
		dataType: "json",
        success: function(msg) {
			if(msg.error){
				alert(msg.error);
			}else{
				var customerDetails=msg.allCustomer;

				$('#addcustomer_id').html(msg.data);
            	$('.invoiceto').select2({
					placeholder: 'Select Customer',
					dropdownParent: $('.invoice-customer')
				});

				renderDetails =
					'<div class="customer-details mt-1">' +
					'<p class="mb-25">' +
					customerDetails[msg.selected].name +
					'</p>' +
					'<p class="mb-25">' +
					customerDetails[msg.selected].address +
					'</p>' +
					'<p class="mb-25">' +
					customerDetails[msg.selected].zone +
					'</p>' +
					'<p class="mb-0">' +
					customerDetails[msg.selected].contact_no_b +
					'</p>' +
					'<p class="mb-0">' +
					customerDetails[msg.selected].custCode +
					'</p>' +
					'<p class="mb-0">' +
					customerDetails[msg.selected].email +
					'</p>' +
					'</div>';
				$('.row-bill-to').find('.customer-details').remove();
				$('.row-bill-to').find('.col-bill-to').append(renderDetails);
			}
			$('#add-new-customer-sidebar').modal('hide');
        }
    });
}
</script>
@endpush