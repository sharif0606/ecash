@extends('layout.admin.admin_master')
@section('title', 'Replace sale')
@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/plugins/forms/pickers/form-flat-pickr.css">
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/css/pages/app-invoice.css">
<style>
	.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-width: 0 0px 0 0;
	}
	.pr-0{
		padding-right: 0 !important;
	}
</style>
@endpush
@section('content')

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

<section class="invoice-add-wrapper">
	<form action="{{route(currentUser().'.replaceBill')}}" method="POST">
		@csrf
		<div class="row invoice-add">
			<!-- Invoice Add Left starts -->
			<div class="col-xl-9 col-md-8 col-12 pr-0">
				<div class="card invoice-preview-card">
					<!-- Header starts -->
					<div class="card-body invoice-padding pb-0">
						<div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
							<div>
								<div class="logo-wrapper">
									<h3 class="text-primary invoice-logo"><img src="{{asset("storage/images/company/$company->company_logo")}}" alt="" /> {{$company->company_name}}</h3>
								</div>
								<p class="card-text mb-25">{{$company->company_add_a}}</p>
								<p class="card-text mb-25">{{$company->company_add_b}}</p>
								<p class="card-text mb-0">{{$company->contact_number}}</p>
							</div>
							<div class="invoice-number-date mt-md-0 mt-2">
								<div class="d-flex align-items-center justify-content-md-end mb-1">
									<h4 class="invoice-title">Invoice</h4>
									<div class="input-group input-group-merge invoice-edit-input-group">
										<div class="input-group-text"><i data-feather="hash"></i></div>
										<input type="text" class="form-control invoice-edit-input" name="bill_no" readonly/>
									</div>
								</div>
								<div class="d-flex align-items-center mb-1">
									<span class="title">Old Invoice:</span>
									<div class="input-group input-group-merge invoice-edit-input-group">
										<div class="input-group-text"><i data-feather="hash"></i></div>
										<input type="text" name="bill_ref" class="form-control invoice-edit-input" value="{{$oldid}}" readonly />
									</div>
								</div>
								<div class="d-flex align-items-center mb-1">
									<span class="title">Date:</span>
									<input type="text" name="bill_date" class="form-control invoice-edit-input date-picker" />
								</div>
							</div>
						</div>
					</div><!-- Header ends -->
					<hr class="invoice-spacing" />
					<!-- Address and Contact starts -->
					<div class="card-body invoice-padding pt-0">
						<div class="row row-bill-to invoice-spacing">
							<div class="col-xl-8 mb-lg-1 ps-0">
								<h6 class="invoice-to-title">Invoice To:</h6>
								<div class="invoice-customer">
									<select class="invoiceto form-select" id="addcustomer_id" name="customer_id">
										<option></option>
										@if($allCustomer)
											@foreach ($allCustomer as $cust)
												<option value="{{$cust['id']}}" @if($bill->customer_id==$cust['id']) selected @endif>{{$cust['custCode']}} - {{$cust['name']}}</option>
											@endforeach
										@endif
									</select>
								</div>
    							<div class="col-bill-to">
    							
    								<div class="customer-details mt-1">
    									<p class="mb-25">{{$bill->customer->name}}</p>
    									<p class="mb-25">{{$bill->customer->address}}</p>
    									<p class="mb-25">@if($bill->customer->zone) {{$bill->customer->zone->name}} @endif</p>
    									<p class="mb-25">{{$bill->customer->contact_no_b}}</p>
    									<p class="mb-25">{{$bill->customer->custCode}}</p>
    									<p class="mb-25">{{$bill->customer->email}}</p>
    								</div>
    							</div>
							</div>
							<div class="col-xl-4 p-0 ps-xl-2 mt-xl-0 mt-2">
								<h6 class="mb-2">Payment Info</h6>
								<div class=" mt-1">
    						        
								</div>
							</div>
						</div>
					</div><!-- Address and Contact ends -->
					<!-- Product Details starts -->
					<div class="card-body invoice-padding invoice-product-details">
						<div class="source-item">
							<div data-repeater-list="products">
								<div class="repeater-wrapper" data-repeater-item>
									<div class="row product_row">
										<div class="col-12 d-flex product-details-border position-relative pe-0">
											<div class="row w-100 pe-lg-0 pe-1 py-2">
												<div class="col-lg-6 col-12 mb-lg-0 mb-2 mt-lg-0 mt-2 item-select2">
													<p class="card-text col-title mb-md-50 mb-0">Item</p>
													<select class="form-select item-details" name="product_id" required>
														<option value="" selected>Select Product</option>
															@if($allProduct)
																@foreach ($allProduct as $pro)
																	<option value="{{$pro->id}}">{{$pro->name}} - {{$pro->brand->name}} - {{$pro->modelName}} - {{$pro->ram}} - {{$pro->storage}} - {{$pro->color}}</option>
																@endforeach
															@endif
													</select>
												</div>
												<div class="col-lg-2 col-12 my-lg-0 my-2">
													<p class="card-text col-title mb-md-2 mb-0">Price</p>
													<input type="text" class="form-control sub_price" onkeyup="totalInvoice()" name="sub_price" readonly/>
													<div class="mt-2">
														<span>Discount:</span>
														<span class="discount">0%</span>
													</div>
												</div>
												<div class="col-lg-2 col-12 my-lg-0 my-2">
													<p class="card-text col-title mb-md-2 mb-0">Qty</p>
													<input type="number" class="form-control qty" onkeyup="totalInvoice()" value="0" name="qty" />
													<input type="hidden" class="maxqty" value="0"/>
													<div class="mt-2">
														<span>Tax:</span>
														<span class="tax ms-50" data-bs-toggle="tooltip" data-bs-placement="top">0%</span>
													</div>
												</div>
												<div class="col-lg-2 col-12 mt-lg-0 mt-2">
													<p class="card-text col-title mb-md-50 mb-0">Price</p>
													<input type="text" name="price" class="form-control price" onkeyup="totalInvoice()" readonly>
												</div>
											</div>
											<div  class="d-flex flex-column align-items-center justify-content-between border-start invoice-product-actions py-50 px-25">
												<i data-feather="x" class="cursor-pointer font-medium-3" data-repeater-delete></i>
												<div class="dropdown">
													<i class="cursor-pointer more-options-dropdown me-0" data-feather="settings" id="dropdownMenuButton" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
													<div class="dropdown-menu dropdown-menu-end item-options-menu p-50" aria-labelledby="dropdownMenuButton">
														<div class="mb-1">
															<label for="discount-input" class="form-label">Discount(%)</label>
															<input type="number" class="form-control discount-input" name="discount" />
														</div>
														<div class="form-row mt-50">
															<div class="mb-1">
																<label for="tax-1-input" class="form-label">Tax(%)</label>
																<input type="number" class="form-control tax-input" name="tax" />
															</div>
														</div>
														<div class="form-row mt-50">
															<div class="mb-1">
																<label for="tax-1-input" class="form-label">Actual Payment</label>
																<input type="number" class="form-control actual_payment" name="actual_payment" />
															</div>
														</div>
														<div class="dropdown-divider my-1"></div>
														<div class="d-flex justify-content-between">
															<button type="button" class="btn btn-outline-primary btn-apply-changes">Apply</button>
															<button type="button" class="btn btn-outline-secondary">Cancel</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row mt-1">
								<div class="col-12 px-0">
									<button type="button" class="btn btn-primary btn-sm btn-add-new" data-repeater-create>
										<i data-feather="plus" class="me-25"></i>
										<span class="align-middle">Add Item</span>
									</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Product Details ends -->

					<!-- Invoice Total starts -->
					<div class="card-body invoice-padding">
						<div class="row invoice-sales-total-wrapper">
						    <div class="col-md-12 order-md-1 order-2 mt-md-0 mt-3">
						        <label for="salesman" class="form-label">Sales Man Name:</label>
						        <input type="text" class="form-control" id="sales_person" name="sales_person" value="{{$bill->sales_person}}" />
							</div>
							<div class="col-md-12 order-md-1 order-2 mt-md-0 mt-3">
							    <div class="mb-1">
							        <label for="note" class="form-label">Replace Note:</label>
							        <textarea class="form-control" rows="2" id="cancel_reason" name="cancel_reason"></textarea>
    							</div>
							</div>
							<div class="col-md-12 order-md-1 order-2 mt-md-0 mt-3">
							    <div class="mb-1">
							        <label for="note" class="form-label">Shop Note:</label>
							        <textarea class="form-control" rows="2" id="note" name="note"></textarea>
    							</div>
							</div>
						</div>
					</div>
					<!-- Invoice Total ends -->

					<hr class="invoice-spacing mt-0" />
				</div>
			</div>
		<!-- Invoice Add Left ends -->

		<!-- Invoice Add Right starts -->
		<div class="col-xl-3 col-md-4 col-12">
			<div class="card">
                <div class="card-body">
        			<div class="">	
        			    <p class="mb-50">Accept payments via</p>
        				<select class="form-select" name="bill_term" onchange="check_term(this.value)">
        					<option value="1">Cash</option>
        					<option value="2">Bank Card </option>
        					<option value="3">Bank Cheque</option>
        					<option value="4">Mobile Bank</option>
        				</select>
        			</div>
        			<div class="mt-1 bank_name" style="display: none">
        				<p class="mb-50">Bank Name</p>
        				<input type="text" class="form-control" name="bank_name" >
        			</div>
        			<div class="mt-1 cheque_no" style="display: none">
        				<p class="mb-50">Check No</p>
        				<input type="text" class="form-control" name="cheque_no" >
        			</div>
        				
        			<div class="mt-1 mobile_bank" style="display: none">
        				<p class="mb-50">Mobile Bank Name</p>
        				<select class="form-control" name="mbank_name" >
        				    @foreach(mobile_bank() as $i=>$m)
        				        <option value="{{$i}}">{{$m}}</option>
        				    @endforeach
        				</select>
        			</div>
        			<div class="mt-1 mobile_bank" style="display: none">
        				<p class="mb-50">Customer Number</p>
        				<input type="text" class="form-control" name="sender_no" >
        			</div>
        			<div class="mt-1 mobile_bank" style="display: none">
        				<p class="mb-50">Receiver Number</p>
        				<input type="text" class="form-control" name="receiver_no" >
        			</div>
        			<div class="mt-1 mobile_bank" style="display: none">
        				<p class="mb-50">Note</p>
        				<input type="text" class="form-control" name="m_note" >
        			</div>
        		</div>
        	</div>
        	<div class="card mt-2">
					<div class="card-body">
					    <div class="invoice-total-wrapper" style="max-width: 100%;">
							<div class="invoice-total-item">
    							<p class="invoice-total-title">Subtotal:</p>
    							<p class="invoice-total-amount sub_total_">0</p>
    							<input type="hidden" name="sub_total_i" class="sub_total_i">
							</div>
							<div class="invoice-total-item">
    							<p class="invoice-total-title">Discount:</p>
    							<p class="invoice-total-amount discount_">0</p>
    							<input type="hidden" name="discount_i" class="discount_i">
							</div>
							<div class="invoice-total-item">
    							<p class="invoice-total-title">Tax:</p>
    							<p class="invoice-total-amount tax_">0</p>
    							<input type="hidden" name="tax_i" class="tax_i">
							</div>
							<hr class="my-50" />
							<div class="invoice-total-item">
    							<p class="invoice-total-title">Total:</p>
    							<p class="invoice-total-amount total_">0</p>
    							<input type="hidden" name="total_i" class="total_i">
							</div>
							<div class="invoice-total-item">
    							<p class="invoice-total-title">Actual Total:</p>
    							<p class="invoice-total-amount actotal_">0</p>
    							<input type="hidden" name="actotal_i" class="actotal_i">
							</div>
							<div class="invoice-total-item">
								<p class="invoice-total-title">Old Pay:</p>
								<p class="invoice-total-amount p-3 pt-0 pb-0 pr-0">{{$bill->actual_amount - $bill->total_due}}</p>
							</div>
							<div>
    							<p class="invoice-total-title">Pay: 
    							    <input type="text" name="total_ip" onkeyup="cal_final_change(this.value)" class="total_ip form-control">
    							    <input type="hidden" name="old_pay" class="form-control" value="{{$bill->actual_amount - $bill->total_due}}">
    							</p>
							</div>
							<div>
    							<p class="invoice-total-title">Change/Due:
    							    <input type="text" class="total_change form-control">
    							</p>
							</div>
							<div id="due_date">
    							<p class="invoice-total-title">Due Date:
    							    <input type="text" name="due_date" class="due_date form-control date-picker">
    							</p>
							</div>
						</div>
					</div>
				</div>
					
			<div class="card mt-2">
				<div class="card-body">
				<button type="submit" class="btn btn-outline-primary w-100">Save</button>
				</div>
			</div>
		</div>
		<!-- Invoice Add Right ends -->
	</form>
	</div>
</section>

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

@endsection

@push('scripts')
<script src="{{asset('/')}}assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{asset('/')}}assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{asset('/')}}assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<script>
function check_term(e){
	$('.bank_name').hide();
	$('.cheque_no').hide();
	$('.mobile_bank').hide();
	if(e==2){
		$('.bank_name').show();
	}if(e==3){
		$('.bank_name').show();
		$('.cheque_no').show();
	}if(e==4){
		$('.mobile_bank').show();
	}
}
$(function () {
	'use strict';
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

	var productList={
	  @if($allProduct)
	  	@foreach ($allProduct as $pro)
		  {{$pro->id}}:[
			@if($pro->stocks)
			  @foreach($pro->stocks as $stock)
			  	@if($stock->stock>0)
			  		{
						batchId: '{{$stock->batchId}}',
						price: '{{$stock->sellPrice}}',
						buyprice: '{{$stock->buyPrice}}',
						tax: '{{$stock->tax}}',
						discount: '{{$stock->discount}}',
						stock: '{{$stock->stock}}',
						stockId: '{{$stock->id}}',
						serialNo: '{{$stock->serialNo}}',
						ram: '{{$stock->ram}}',
						storage: '{{$stock->storage}}',
						color: '{{$stock->color}}',
						imei_1: '{{$stock->imei_1}}'
					},
				@endif
			  @endforeach
			@endif
		  ],
		 @endforeach
	  @endif
	};

	var applyChangesBtn = $('.btn-apply-changes'),
	  discount,
	  tax1,
	  tax2,
	  discountInput,
	  tax1Input,
	  tax2Input,
	  sourceItem = $('.source-item'),
	  date = new Date(),
	  datepicker = $('.date-picker'),
	  dueDate = $('.due-date-picker'),
	  select2 = $('.invoiceto'),
	  countrySelect = $('#customer-country'),
	  btnAddNewItem = $('.btn-add-new '),
	  productDetails = productList,
	  customerDetails = data_list;
  
	// init date picker
	if (datepicker.length) {
	  datepicker.each(function () {
		$(this).flatpickr({
		  defaultDate: date
		});
	  });
	}
  
	if (dueDate.length) {
	  dueDate.flatpickr({
		//defaultDate: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 5)
	  });
	}
  
	// Country Select2
	if (countrySelect.length) {
	  countrySelect.select2({
		placeholder: 'Select country',
		dropdownParent: countrySelect.parent()
	  });
	}
  
	// Close select2 on modal open
	$(document).on('click', '.add-new-customer', function () {
	  select2.select2('close');
	});
  
	// Select2
	if (select2.length) {
	  select2.select2({
		placeholder: 'Select Customer',
		dropdownParent: $('.invoice-customer')
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
	//product select search init
	$('.item-details').select2({
		placeholder: 'Select Product'
	});
  
	// Repeater init
	if (sourceItem.length) {
	  sourceItem.on('submit', function (e) {
		e.preventDefault();
	  });
	  sourceItem.repeater({
		show: function () {
		  $(this).slideDown();
		  $(".item-select2 span").data("select2-id", "2").hide();
		  $('.item-details').select2({
			placeholder: 'Select Product'
		  });
		},
		hide: function (e) {
		  $(this).slideUp();
		}
	  });
	}
  
	// Prevent dropdown from closing on tax change
	$(document).on('click', '.tax-select', function (e) {
	  e.stopPropagation();
	});
  
	// On tax change update it's value
	function updateValue(listener, el) {
	  listener.closest('.repeater-wrapper').find(el).text(listener.val());
	}
  
	// Item details select onchange
	$(document).on('change', '.item-details', function () {
	  var $this = $(this);
		$this.find(".batchhidetry").hide();
		var datas = productDetails[$this.val()];
		var value='<option value="">Select Batch</option>';
		for(var i in datas){
			value+='<option value="'+datas[i].batchId+'-'+datas[i].price+'-'+datas[i].tax+'-'+datas[i].discount+'-'+datas[i].stockId+'-'+datas[i].buyprice+'-'+datas[i].stock+'">serialNo:'+datas[i].serialNo+' - ram:'+datas[i].ram+' - storage:'+datas[i].storage+' - color:'+datas[i].color+' - Price:'+datas[i].price+'</option>';
		}

		if ($this.parent().find('.selbatch').length)
			$this.parent().find('.selbatch').html(value);
		else
			$this.parent().append('<select class="form-control mt-2 selbatch" rows="2" name="batchId" onchange="setPrice(this.value,this)">' + value + '</select>');
		
	});
	if (btnAddNewItem.length) {
	  btnAddNewItem.on('click', function () {
		if (feather) {
		  // featherSVG();
		  feather.replace({ width: 14, height: 14 });
		}
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		  return new bootstrap.Tooltip(tooltipTriggerEl);
		});
	  });
	}

	if (applyChangesBtn.length) {
		$(document).on('click', '.btn-apply-changes', function (e) {
			$(this).parents('.product_row').find('.tax').text($(this).parents('.product_row').find('.tax-input').val()+'%');
			$(this).parents('.product_row').find('.discount').text($(this).parents('.product_row').find('.discount-input').val()+'%');
			totalInvoice();
		});
	}
	$('.data_delete').click(function(){
		$(this).parents(".repeater-wrapper").replaceWith("");
		totalInvoice()
	})

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

function setPrice(e,s){
	var data=e.split('-');
	$(s).parents('.product_row').find('.sub_price').val(data[1]);
	$(s).parents('.product_row').find('.price').val(data[1]);
	$(s).parents('.product_row').find('.qty').val(1);
	$(s).parents('.product_row').find('.maxqty').val(data[6]);

	$(s).parents('.product_row').find('.tax-input').val(data[2]);
	$(s).parents('.product_row').find('.tax').text(data[2]+'%');

	$(s).parents('.product_row').find('.discount-input').val(data[3]);
	$(s).parents('.product_row').find('.discount').text(data[3]+'%');
	totalInvoice()
}
function totalInvoice(){
	var sub_total_=0;
	var discount_=0;
	var tax_=0;
	var total_=0;
	var actotal_=0;
	$('.sub_price').each(function(){
		var qty=parseFloat($(this).parents('.product_row').find('.qty').val());
		var maxqty=parseFloat($(this).parents('.product_row').find('.maxqty').val());
        if(qty>maxqty){
            alert("You cannot sell more than "+maxqty);
            $(this).parents('.product_row').find('.qty').val(maxqty);
            $(this).parents('.product_row').find('.qty').focus();
            qty=maxqty;
        }
		var sub_price=parseFloat($(this).val());
		var discount=parseFloat($(this).parents('.product_row').find('.discount-input').val());
		var tax=parseFloat($(this).parents('.product_row').find('.tax-input').val());
		var actual_payment=parseFloat($(this).parents('.product_row').find('.actual_payment').val());
		
        var actual_payment=actual_payment>0?actual_payment:sub_price;
        
		var sub_t=(sub_price*qty);
		var acsub_t=(actual_payment*qty);
		var discount_t=tax_t=acdiscount_t=actax_t=0;
		
		if(discount>0){
			discount_t=(sub_t*(discount/100));
			acdiscount_t=(acsub_t*(discount/100));
		}
		if(tax>0){
			tax_t=((sub_price*qty)*(tax/100));
			actax_t=(acsub_t*(tax/100));
		}

		sub_total_ += sub_t;// final sub total amount
		discount_+=discount_t;// final discount amount
		tax_+=tax_t;// final tax amount

		sub_t=((sub_t+tax_t)-discount_t);// product wise total
		acsub_t=((acsub_t+actax_t)-acdiscount_t);// product wise total
		
		total_+=sub_t;// final total amount
		actotal_+=acsub_t;// final total amount
		$(this).parents('.product_row').find('.price').val(sub_t);// product wise total add to input
		
	})
	$('.sub_total_i').val(sub_total_);
	$('.discount_i').val(discount_);
	$('.tax_i').val(tax_);
	$('.total_i').val(total_);
	$('.actotal_i').val(actotal_);

	$('.sub_total_').text(sub_total_);
	$('.discount_').text(discount_);
	$('.tax_').text(tax_);
	$('.total_').text(total_);
	$('.actotal_').text(actotal_);
}

function cal_final_change(predefinedamount=""){
	
    var total_cal=parseFloat($('.actotal_').text());
    var totalpaid=parseFloat(predefinedamount) > 0 ? parseFloat(predefinedamount) : parseFloat($('.total_ip').val());
    var amremain=0;
    if(total_cal>totalpaid){
        amremain=(total_cal-totalpaid);
        $('.total_change').val(amremain.toFixed(2) +' Due');
        $('#due_date').show();
		$('.total_change').css('color','red');
    }else if(total_cal<totalpaid){
        amremain=(totalpaid-total_cal);
        $('.total_change').val(amremain.toFixed(2) +' Change');
        $('#due_date').hide();
		$('.total_change').css('color','green');
    }else{
        $('.total_change').val(0);
        $('#due_date').hide();
		$('.total_change').css('color','black');
    }
}
</script>

@endpush