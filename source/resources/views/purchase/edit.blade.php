@extends('layout.admin.admin_master')
@section('title', 'Edit purchase')
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
	<form action="{{route(currentUser().'.updatePurchase')}}" method="POST">
		@csrf
		<input type="hidden" value="{{Session::get('user')}}" name="userId">
		<input type="hidden" value="{{encryptor('encrypt', $purchase->id)}}" name="id">
		<div class="row invoice-add">
			<!-- Invoice Add Left starts -->
			<div class="col-xl-9 col-md-8 col-12">
				<div class="card invoice-preview-card">
					<!-- Header starts -->
					<div class="card-body invoice-padding pb-0">
						<div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
							<div>
								<div class="logo-wrapper">
									<h3 class="text-primary invoice-logo"><img width="100px" src="{{asset("storage/images/company/$company->company_logo")}}" alt="" /> {{$company->company_name}}</h3>
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
										<input type="text" class="form-control invoice-edit-input" required name="ref_no" value="{{$purchase->ref_no}}" />
									</div>
								</div>
								<div class="d-flex align-items-center mb-1">
									<span class="title">Date:</span>
									<input type="text" name="purchase_date" class="form-control invoice-edit-input date-picker" value="{{$purchase->purchase_date}}" />
								</div>
								<div class="d-flex align-items-center">
									<span class="title">Due Date:</span>
									<input type="text" name="due_date" class="form-control invoice-edit-input due-date-picker" value="{{$purchase->due_date}}" />
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
								<div class="invoice-supplier">
									<select class="invoiceto form-select" id="addsupplier_id" name="sup_id" required>
										<option></option>
										@if($allSupplier)
											@foreach ($allSupplier as $cust)
												<option value="{{$cust['id']}}" @if($purchase->sup_id==$cust['id']) selected @endif>{{$cust['supCode']}} - {{$cust['name']}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<div class="col-bill-to">
    								<h6 class="mt-1">Bill To:</h6>
    								<div class="supplier-details">
    									<p class="mb-25">{{$purchase->supplier->name}}</p>
    									<p class="mb-25">{{$purchase->supplier->address}}</p>
    									<p class="mb-25">@if($purchase->supplier->zone) {{$purchase->supplier->zone->name}} @endif</p>
    									<p class="mb-25">{{$purchase->supplier->contact_no_b}}</p>
    									<p class="mb-25">{{$purchase->supplier->supCode}}</p>
    									<p class="mb-25">{{$purchase->supplier->email}}</p>
    								</div>
    							</div>
							</div>
							<div class="col-xl-4 p-0 ps-xl-2 mt-xl-0 mt-2">
								<h6 class="mb-2">Payment Info</h6>
								<div class=" mt-1">
    						        @if($purchase->purchase_term==1)
    						            Cash
								    @endif
    						        @if($purchase->purchase_term==2)
    									<b>Bank Card - </b>{{ $purchase->bank_name }}
								    @endif
    						        @if($purchase->purchase_term==3)
    									<b>Bank:</b>{{ $purchase->bank_name }}<br>
    								    <b>Cheque No:</b>{{ $purchase->cheque_no }}
								    @endif
    						        @if($purchase->purchase_term==4)
    									<b>Bank:</b>{{ mobile_bank()[$purchase->mbank_name] }}<br>
    								    <b>Sender:</b>{{ $purchase->sender_no }}<br>
    								    <b>Supplier:</b>{{ $purchase->receiver_no }}<br>
    								    <b>Note:</b>{{ $purchase->m_note }}<br>
								    @endif
								</div>
							</div>
						</div>
					</div><!-- Address and Contact ends -->
					<!-- Product Details starts -->
					<div class="card-body invoice-padding invoice-product-details">
						<div class="source-item">
							<div data-repeater-list="products">
                        @if($items)
                            @foreach ($items as $item)
								<div class="repeater-wrapper item_id" data-repeater-item>
									<div class="row product_row">
										<div class="col-12 d-flex product-details-border position-relative pe-0">
											<div class="row w-100 pe-lg-0 pe-1 py-2">
												<div class="col-lg-6 col-12 mb-lg-0 mb-2 mt-lg-0 mt-2 item-select2">
                                                    <input type="hidden" name="old_id" value="{{$item->id}}">
													<p class="card-text col-title mb-md-50 mb-0">Item</p>
													<select class="form-select item-details" name="product_id" required>
														<option value="" selected>Select Product</option>
                                                        @if($allProduct)
                                                            @foreach ($allProduct as $pro)
                                                                <option value="{{$pro->id}}" @if($item->item_id==$pro->id) selected @endif>{{$pro->name}} - {{$pro->brand->name}} </option>
                                                            @endforeach
                                                        @endif
													</select>
													<input type="hidden" class="batchId" name="batchId" value="{{$item->batchId}}"/>
                                                    <button type="button" class="mt-2 btn btn-info btn-sm btn-add-new add-new-product"  data-bs-toggle="modal" data-bs-target="#add-new-product-sidebar">
														<i data-feather="plus" class="me-25"></i>
														<span class="align-middle">Add New Product</span>
													</button>
													<button type="button" class="add-new-details mt-2 btn btn-info btn-sm waves-effect waves-float waves-light">
														<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
														<span class="align-middle">Add Details</span>
													</button>
												</div>
												<div class="col-lg-2 col-12 my-lg-0 my-2">
													<p class="card-text col-title mb-md-2 mb-0">Buy Price</p>
													<input type="text" class="form-control buy_price" onkeyup="totalInvoice()" name="buyPrice" value="{{$item->price}}"/>
													<div class="mt-2">
														<span>Serial No:</span>
														<span class="sl_no">{{$item->batch->serialNo}}</span>
													</div>
													<div class="mt-2">
														<span>IMEI No 1:</span>
														<span class="imei_o">{{$item->batch->imei_1}}</span>
													</div>
												</div>
												<div class="col-lg-2 col-12 my-lg-0 my-2">
													<p class="card-text col-title mb-md-50 mb-0">Sell Price</p>
													<input type="text" name="sellPrice" class="form-control sell_price" value="{{$item->batch->sellPrice}}">
													<div class="mt-2">
														<span>Ram:</span>
														<span class="ram">{{$item->batch->ram}}</span>
													</div>
													<div class="mt-2">
														<span>IMEI No 2:</span>
														<span class="imei_t">{{$item->batch->imei_2}}</span>
													</div>
												</div>
												<div class="col-lg-2 col-12 mt-lg-0 mt-2">
													<p class="card-text col-title mb-md-2 mb-0">Qty</p>
													<input type="number" class="form-control qty" readonly onkeyup="totalInvoice()" name="qty" value="{{$item->qty}}" />
													<div class="mt-2">
														<span>Color:</span>
														<span class="color">{{$item->batch->color}}</span>
													</div>
													<div class="mt-2">
														<span>Storage:</span>
														<span class="storage">{{$item->batch->storage}}</span>
													</div>
												</div>
											</div>
											<div  class="d-flex flex-column align-items-center justify-content-between border-start invoice-product-actions py-50 px-25">
												<i data-feather="x" class="cursor-pointer font-medium-3" onclick="remove_this(this)" data-repeater-delete></i>
												<div class="dropdown">
													<i class="cursor-pointer more-options-dropdown me-0" data-feather="settings" id="dropdownMenuButton" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
													<div class="dropdown-menu dropdown-menu-end item-options-menu p-50" aria-labelledby="dropdownMenuButton">
														<div class="mb-1">
															<label for="discount-input" class="form-label">Discount(%)</label>
															<input type="number" class="form-control discount-input" name="discount" value="{{$item->discount}}"/>
														</div>
														<div class="form-row mt-50">
															<div class="mb-1 col-md-6">
																<label for="tax-1-input" class="form-label">Tax(%)</label>
																<input type="number" class="form-control tax-input" name="tax" value="{{$item->tax}}" />
															</div>
														</div>
														
														<div class="form-row row mt-50">
															<div class="mb-1 col-6">
																<label for="tax-1-input" class="form-label">Unlock Qty</label>
															</div>
															<div class="mb-1 col-6">
																<div class="form-check form-check-danger form-switch">
                                                                    <input type="checkbox" class="form-check-input unlock_qty">
                                                                </div>
															</div>
															<span class="text-danger">If you unlock qty, your unique serial number will not affected then.</span>
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
                            @endforeach
                        @endif
							</div>
							<div class="row mt-1">
								<div class="col-12 px-0">
									<button type="button" class="btn btn-primary btn-sm" data-repeater-create>
										<i data-feather="plus" class="me-25"></i>
										<span class="align-middle">Add Item</span>
									</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Product Details ends -->
					<div class="card-body invoice-padding">
						<div class="row invoice-sales-total-wrapper">
						    <div class="col-md-12 order-md-1 order-2 mt-md-0 mt-3">
    							<div class="d-flex align-items-center mb-1">
    								<label for="salesperson" class="form-label">Shop Note:</label>
    								<textarea class="form-control" rows="2" id="note" name="note">{{$purchase->note}}</textarea>
    							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
            <!-- Invoice Add Left ends -->

            <!-- Invoice Add Right starts -->
            <div class="col-xl-3 col-md-4 col-12">
                <div class="">
                    <p class="mb-50">pay via</p>
                    <select class="form-select purchase_term" name="purchase_term" onchange="check_term(this.value)">
                        <option value="1">Cash</option>
                        <option value="2">Bank Card </option>
                        <option value="3">Bank Cheque</option>
						<option value="4">Mobile Bank</option>
                    </select>
                </div>
                <div class="mt-1 bank_name" style="display: none">
                    <p class="mb-50">Bank Name</p>
                    <input type="text" class="form-control" name="bank_name" value="{{$purchase->bank_name}}">
                </div>
                <div class="mt-1 cheque_no" style="display: none">
                    <p class="mb-50">Check No</p>
                    <input type="text" class="form-control" name="cheque_no" value="{{$purchase->cheque_no}}" >
                </div>
                
				<div class="mt-1 mobile_bank" style="display: none">
					<p class="mb-50">Mobile Bank Name</p>
					<select class="form-control" name="mbank_name" >
					    @foreach(mobile_bank() as $i=>$m)
					        <option value="{{$i}}" @if($purchase->mbank_name==$i) selected @endif >{{$m}}</option>
					    @endforeach
					</select>
				</div>
				<div class="mt-1 mobile_bank" style="display: none">
					<p class="mb-50">Sender Number</p>
					<input type="text" class="form-control" name="sender_no" value="{{$purchase->sender_no}}"  >
				</div>
				<div class="mt-1 mobile_bank" style="display: none">
					<p class="mb-50">Supplier Number</p>
					<input type="text" class="form-control" name="receiver_no" value="{{$purchase->receiver_no}}"  >
				</div>
				<div class="mt-1 mobile_bank" style="display: none">
					<p class="mb-50">Note</p>
					<input type="text" class="form-control" name="m_note" value="{{$purchase->m_note}}"  >
				</div>
				<div class="card mt-2">
					<div class="card-body">
					    <div class="invoice-total-wrapper" style="max-width: 100%;">
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Subtotal:</p>
                                <p class="invoice-total-amount sub_total_">{{$purchase->sub_total}}</p>
                                <input type="hidden" name="sub_total_i" class="sub_total_i" value="{{$purchase->sub_total}}">
                            </div>
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Discount:</p>
                                <p class="invoice-total-amount discount_">{{$purchase->total_dis}}</p>
                                <input type="hidden" name="discount_i" class="discount_i" value="{{$purchase->total_dis}}">
                            </div>
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Tax:</p>
                                <p class="invoice-total-amount tax_">{{$purchase->total_tax}}</p>
                                <input type="hidden" name="tax_i" class="tax_i" value="{{$purchase->total_tax}}">
                            </div>
                            <hr class="my-50" />
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Total:</p>
                                <p class="invoice-total-amount total_">{{$purchase->total_amount}}</p>
                                <input type="hidden" name="total_i" class="total_i" value="{{$purchase->total_amount}}">
                            </div>
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Pay:
                                    <input type="text" name="total_ip" class="total_ip form-control" onkeyup="cal_final_change(this.value)" value="@if($purchase->total_amount > $purchase->total_due){{$purchase->total_amount - $purchase->total_due}} @else {{$purchase->total_amount}} @endif">
                                </p>
                            </div>
                            <div class="invoice-total-item">
    							<p class="invoice-total-title">Change/Due:
    							    <input type="text" class="total_change form-control">
    							</p>
							</div>
							<div class="invoice-total-item" id="due_date">
    							<p class="invoice-total-title">Due Date:
    							    <input type="text" name="due_date" value="{{$purchase->due_date}}" class="due_date form-control date-picker">
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
	    </div>
	</form>
</section>

	<!-- Add New supplier Sidebar -->
	<div class="modal modal-slide-in fade" id="add-new-supplier-sidebar" aria-hidden="true">
		<div class="modal-dialog sidebar-lg">
			<div class="modal-content p-0">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				<div class="modal-header mb-1">
					<h5 class="modal-title">
						<span class="align-middle">Add Supplier</span>
					</h5>
				</div>
				<div class="modal-body flex-grow-1">
					<form id="supplierForm">
						<div class="mb-1">
							<label for="supplier-name" class="form-label">Supplier Name  <span class="text-danger sup">*</span></label>
							<input type="text" name="name" id="cname" class="form-control"/>
							<small class="d-none text-danger mb-3 cname">
								Supplier Name is required
							</small>
						</div>
						<div class="mb-1">
							<label for="supCode" class="form-label">Phone No. 1  <span class="text-danger sup">*</span></label>
							<input type="text" class="form-control" id="supCode" name="supCode"/>
							<small class="d-none text-danger mb-3 supCode">
								Supplier Mobile No is required
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
							<label for="address" class="form-label">Supplier Address</label>
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
							<button type="button" class="btn btn-primary me-1" onclick="saveSupplier()">Add</button>
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Add New supplier Sidebar -->

	<!-- Add New product details Modal -->
	<div class="modal fade" id="add-new-product-details" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content p-0">
				<div class="modal-header mb-1">
					<h5 class="modal-title">
						<span class="align-middle">Add Details</span>
					</h5>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body">
					<form id="productDetails">
						<div class="row mb-1">
							<div class="col">
								<label for="serialNo" class="form-label">Serial No</label>
								<input type="text" class="form-control" name="serialNo" id="serialNo" placeholder="Serial" />
							</div>
							<div class="col">
								<label for="imei_1" class="form-label">IMEI No</label>
								<input type="text" class="form-control" name="imei_1" id="imei_1" placeholder="IMEI" />	
							</div>
						</div>
						<div class="row mb-1">
							<div class="col">
								<label for="ram" class="form-label">Ram</label>
								<input type="text" class="form-control" name="ram" id="ram" placeholder="ram" />
							</div>
							<div class="col">
								<label for="imei_2" class="form-label">IMEI No 2</label>
								<input type="text" class="form-control" name="imei_2" id="imei_2" placeholder="IMEI" />	
							</div>
						</div>
						<div class="row mb-1">
							<div class="col">
								<label for="color" class="form-label">Color</label>
								<input type="text" class="form-control" name="color" id="color" placeholder="color" />
							</div>
							<div class="col">
								<label for="storage" class="form-label">Storage</label>
								<input type="text" class="form-control" name="storage" id="storage" placeholder="Storage" />	
							</div>
						</div>
						<div class="mb-1 d-flex flex-wrap mt-2">
						<button type="button" class="btn btn-primary me-1" data-bs-dismiss="modal" onclick="saveProductDetails()">Add</button>
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Add New product details Modal -->

	<!-- Add New Product Sidebar -->
	<div class="modal modal-slide-in fade" id="add-new-product-sidebar" aria-hidden="true">
		<div class="modal-dialog sidebar-lg">
		<div class="modal-content p-0">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
			<h5 class="modal-title">
				<span class="align-middle">Add Product</span>
			</h5>
			</div>
			<div class="modal-body flex-grow-1">
			<form id="productadd">
				<div class="mb-1">
				<div class="row">
					<div class="col">
						<button type="button" class="btn btn-primary btn-lg">Import</button>  
					</div>
					<div class="col">
						<input type="search" class="form-control" placeholder="Search..."/>
					</div>
				</div>
				</div>
				<div class="mb-1">
					<p class="text-center">OR</p>
				</div>
				<div class="mb-1 position-relative">
					<label for="select-type" class="form-label">Select Type</label>
					<select class="form-select" id="select-type" name="categoryId">
						<option value="">Select Category</option>
						@if($allCat)
							@foreach($allCat as $ac)
								<option value="{{$ac->id}}">{{$ac->name}}</option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="mb-1 position-relative">
					<label for="select-type" class="form-label">Select Brand Name</label>
					<select class="form-select" id="select-type" name="brandId">
						<option value="">Select Brand</option>
						@if($allBrand)
    						@foreach($allBrand as $ac)
    							<option value="{{$ac->id}}">{{$ac->name}}</option>
    						@endforeach
						@endif
					</select>
				</div>
				<div class="mb-1">
					<label for="contact_no_b" class="form-label">Model Name</label>
					<input type="text" class="form-control" name="name" />
				</div>
				<div class="mb-1">
					<label for="contact_no_b" class="form-label">Model No</label>
					<input type="text" class="form-control" name="modelNo"/>
				</div>
				<div class="mb-1">
					<label for="contact_no_b" class="form-label">Serial No</label>
					<input type="text" class="form-control" name="serialNo"/>
				</div>
				<div class="mb-1 d-flex flex-wrap mt-2">
				<button type="button" class="btn btn-primary me-1" onclick="saveProduct()">Add</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
			</div>
		</div>
		</div>
	</div>
	<!--/ Add New Product Sidebar -->

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
/* set this obj when new product create btn clicked */
var current_product=false;

$(function () {
	'use strict';
    
	@if($purchase->purchase_term)
		$('.purchase_term').val({{$purchase->purchase_term}})
		check_term({{$purchase->purchase_term}})
	@endif
    var data_list={
	  @if($allSupplier)
	  	@foreach ($allSupplier as $sup)
		  {{$sup->id}}:{
			name: '{{$sup->name}}',
			address: '{{$sup->address}}',
			contact_person: '{{$sup->contact_person}}',
			contact_no_b: '{{$sup->contact_no_b}}',
			supCode: '{{$sup->supCode}}',
			email: '{{$sup->email}}',
			zone: @if($sup->zone)"{{$sup->zone->name}}" @else " " @endif},
		 @endforeach
	  @endif
	};

	var productList={
	    @if($allProduct)
	  	    @foreach ($allProduct as $pro)
			    @if($pro->stocks)
			        @foreach($pro->stocks as $stock)
                        {{$stock->batchId}}:{
                            batchId: '{{$stock->batchId}}',
                            serialNo: '{{$stock->serialNo}}',
                            ram: '{{$stock->ram}}',
                            storage: '{{$stock->storage}}',
                            color: '{{$stock->color}}',
                            imei_1: '{{$stock->imei_1}}',
                            imei_2: '{{$stock->imei_2}}',
                            price: '{{$stock->sellPrice}}',
                            buyprice: '{{$stock->buyPrice}}',
                            tax: '{{$stock->tax}}',
                            discount: '{{$stock->discount}}',
                            stock: '{{$stock->stock}}',
                            stockId: '{{$stock->id}}'
                        },
			        @endforeach
			    @endif
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
	  countrySelect = $('#supplier-country'),
	  btnAddNewItem = $('.btn-add-new '),
	  productDetails = productList,
	  supplierDetails = data_list;
  
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
	$(document).on('click', '.add-new-supplier', function () {
	  select2.select2('close');
	});
  
	// Select2
	if (select2.length) {
	  select2.select2({
		placeholder: 'Select Supplier',
		dropdownParent: $('.invoice-supplier')
	  });
  
	  select2.on('change', function () {
		var $this = $(this),
		  renderDetails =
			'<div class="supplier-details mt-1">' +
			'<p class="mb-25">' +
			supplierDetails[$this.val()].name +
			'</p>' +
			'<p class="mb-25">' +
			supplierDetails[$this.val()].address +
			'</p>' +
			'<p class="mb-25">' +
			supplierDetails[$this.val()].zone +
			'</p>' +
			'<p class="mb-0">' +
			supplierDetails[$this.val()].contact_no_b +
			'</p>' +
			'<p class="mb-0">' +
			supplierDetails[$this.val()].supCode +
			'</p>' +
			'<p class="mb-0">' +
			supplierDetails[$this.val()].email +
			'</p>' +
			'</div>';
		$('.row-bill-to').find('.supplier-details').remove();
		$('.row-bill-to').find('.col-bill-to').append(renderDetails);
	  });
  
	  select2.on('select2:open', function () {
		if (!$(document).find('.add-new-supplier').length) {
		  $(document)
			.find('.select2-results__options')
			.before(
			  '<div class="add-new-supplier btn btn-flat-success cursor-pointer rounded-0 text-start mb-50 p-50 w-100" data-bs-toggle="modal" data-bs-target="#add-new-supplier-sidebar">' +
				feather.icons['plus'].toSvg({ class: 'font-medium-1 me-50' }) +
				'<span class="align-middle">Add New Supplier</span></div>'
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
			$(".item-select2 > span").data("select2-id", "2").hide();
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
        $(this).parents('.product_row').find('.sell_price').val("");
        $(this).parents('.product_row').find('.buy_price').val("");
        $(this).parents('.product_row').find('.qty').val(1);
    });
    
    //$('.item-details').change();
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
			if($(this).parents('.product_row').find('.unlock_qty').prop("checked") == true)
			    $(this).parents('.product_row').find('.qty').removeAttr("readonly")
			else
			    $(this).parents('.product_row').find('.qty').attr("readonly","readonly")
		});
	}
	
	$(document).on('click','.data_delete',function(){
		$(this).parents(".repeater-wrapper").replaceWith("");
		totalInvoice()
	})
	
	$(document).on('click', '.add-new-product', function (e) {
		current_product=$(this);
	});
	
	$(document).on('click', '.add-new-details', function (e) {
		current_product=$(this);
		if(current_product.parents('.product_row').find('.item-details').val()==''){
			alert("You have to select an item first");
			$('#add-new-product-details').modal('hide');
		}else{
		    var batchID=current_product.parents('.product_row').find('.batchId').val();
            $('#serialNo').val(productList[batchID].serialNo);
            $('#ram').val(productList[batchID].ram);
            $('#color').val(productList[batchID].color);
            $('#imei_1').val(productList[batchID].imei_1);
            $('#imei_2').val(productList[batchID].imei_2);
            $('#storage').val(productList[batchID].storage);
            
	        $('#add-new-product-details').modal('show');
		}
	});
});

function saveSupplier(){
	var data=$('#supplierForm').serialize();
	$('.waiting').show();
	$('.saving').hide();
	$.ajax({
        type: "GET",
        url: "{{route(currentUser().'.saveSupplier')}}?"+data,
        data: {  },
		dataType: "json",
        success: function(msg) {
			if(msg.error){
				alert(msg.error);
			}else{
				var supplierDetails=msg.allSupplier;

				$('#addsupplier_id').html(msg.data);
            	$('.invoiceto').select2({
					placeholder: 'Select Supplier',
					dropdownParent: $('.invoice-supplier')
				});

				renderDetails =
					'<div class="supplier-details mt-1">' +
					'<p class="mb-25">' +
					supplierDetails[msg.selected].name +
					'</p>' +
					'<p class="mb-25">' +
					supplierDetails[msg.selected].address +
					'</p>' +
					'<p class="mb-25">' +
					supplierDetails[msg.selected].zone +
					'</p>' +
					'<p class="mb-0">' +
					supplierDetails[msg.selected].contact_no_b +
					'</p>' +
					'<p class="mb-0">' +
					supplierDetails[msg.selected].supCode +
					'</p>' +
					'<p class="mb-0">' +
					supplierDetails[msg.selected].email +
					'</p>' +
					'</div>';
				$('.row-bill-to').find('.supplier-details').remove();
				$('.row-bill-to').find('.col-bill-to').append(renderDetails);
				
			}
			$('#add-new-supplier-sidebar').modal('hide');
        }
    });
}

function saveProduct(){
	var data=$('#productadd').serialize();
	$.ajax({
        type: "GET",
        url: "{{route(currentUser().'.saveProduct')}}?"+data,
        data: {  },
		dataType: "json",
        success: function(msg) {
			if(msg.error){
				alert(msg.error);
			}else{
				current_product.parents('.product_row').find('.item-details').html(msg.data);
			}
			$('#add-new-product-sidebar').modal('hide');
        }
    });
}

function saveProductDetails(){
	var data=$('#productDetails').serialize();
	var batchID=current_product.parents('.product_row').find('.batchId').val();
	$.ajax({
        type: "GET",
        url: "{{route(currentUser().'.saveProductDetails')}}?"+data+'&batchID='+batchID,
        data: {  },
		dataType: "json",
        success: function(msg) {
			if(msg.error){
				alert(msg.error);
			}else{
				current_product.parents('.product_row').find('.sl_no').html(msg.data.serialNo);
				current_product.parents('.product_row').find('.ram').html(msg.data.ram);
				current_product.parents('.product_row').find('.color').html(msg.data.color);
				current_product.parents('.product_row').find('.imei_o').html(msg.data.imei_1);
				current_product.parents('.product_row').find('.imei_t').html(msg.data.imei_2);
				current_product.parents('.product_row').find('.storage').html(msg.data.storage);
			}
			$('#add-new-product-sidebar').modal('hide');
        }
    });
}

function setPrice(e,s){
	var data=e.split('-');
	$(s).parents('.product_row').find('.sell_price').val(data[1]);
	$(s).parents('.product_row').find('.buy_price').val(data[2]);
	totalInvoice()
}

function totalInvoice(){
	var sub_total_=0;
	var discount_=0;
	var tax_=0;
	var total_=0;
	$('.buy_price').each(function(){
		var buy_price=parseFloat($(this).val());
		var qty=parseFloat($(this).parents('.product_row').find('.qty').val());
		var discount=parseFloat($(this).parents('.product_row').find('.discount-input').val());
		var tax=parseFloat($(this).parents('.product_row').find('.tax-input').val());
        
        var buy_price= !isNaN(buy_price)?buy_price:0;
		var qty=!isNaN(qty)?qty:0;
		var discount=!isNaN(discount)?discount:0;
		var tax=!isNaN(tax)?tax:0;
        
		var sub_t=(buy_price*qty);
		var discount_t=0;
		var tax_t=0;
		
		if(discount>0)
			discount_t=(sub_t*(discount/100));
		if(tax>0)
			tax_t=((buy_price*qty)*(tax/100));

		sub_total_ += sub_t;// final sub total amount
		discount_+=discount_t;// final discount amount
		tax_+=tax_t;// final tax amount

		sub_t=((sub_t+tax_t)-discount_t);// product wise total
		
		total_+=sub_t;// final total amount
		//$(this).parents('.product_row').find('.price').val(sub_t);// product wise total add to input
	})
	$('.sub_total_i').val(sub_total_);
	$('.discount_i').val(discount_);
	$('.tax_i').val(tax_);
	$('.total_i').val(total_);
	$('.total_ip').val(total_);

	$('.sub_total_').text(sub_total_);
	$('.discount_').text(discount_);
	$('.tax_').text(tax_);
	$('.total_').text(total_);
}

function cal_final_change(predefinedamount=""){
    var total_cal=parseFloat($('.total_').text());
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

function remove_this(e){
     $(e).closest("[data-repeater-item]").remove();
     totalInvoice();
}

</script>

@endpush