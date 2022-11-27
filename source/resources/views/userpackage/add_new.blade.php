@extends('layout.admin.admin_master')
@section('title', 'Add new user package')
@section('content')
<div class="card card-custom">
	<div class="card-header flex-wrap border-0 pt-6 pb-0">
		<div class="card-title">
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">User Package</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Add</a>
				</li>
			</ul>
		</div>
	</div>
	<!--end::Subheader-->
	<div class="content flex-column-fluid" id="kt_content">
		<div class="row">
			<div class="col-lg-12">
				<!--begin::Card-->
				<div class="card card-custom gutter-b example example-compact">
					<div class="card-header">
						<h3 class="card-title">New User Package</h3>
					</div>
					<!--begin::Form-->
					<form class="form" action="{{ route(currentUser().'.addNewUserPackage') }}" method="POST">
						@csrf
						<input type="hidden" value="{{ Session::get('user') }}" name="userId">
						<div class="card-body">
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Company/Owner Name</label>
									<select name="companyId"
										class="select2 form-control @if($errors->has('companyId')) {{ 'is-invalid' }} @endif"
										required="" style="width:100%">
										<option value="">Select Name</option>
										@if(count($user) > 0)
										@foreach($user as $u)
										<option value="{{ $u->companyId }}">Owner:{{ $u->name }} -
											{{ $u->mobileNumber }} | Company: {{ $u->company_name }}</option>
										@endforeach
										@endif
									</select>
									@if($errors->has('companyId'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('companyId') }}
									</small>
									@endif
								</div>
								<div class="col-lg-4">
									<label>Package</label>
									<select name="packageId"
										class="select2 form-control @if($errors->has('packageId')) {{ 'is-invalid' }} @endif"
										required="" style="width:100%" onchange="get_price(this)">
										<option value="">Select Name</option>
										@if(count($allPackage) > 0)
										@foreach($allPackage as $ap)
										<option value="{{$ap->id}}-{{$ap->duration}}-{{$ap->price}}">{{ $ap->name }}
										</option>
										@endforeach
										@endif
									</select>
									@if($errors->has('packageId'))
									<small class="d-block text-danger mb-3">
										{{ $errors->first('packageId') }}
									</small>
									@endif
								</div>
								<div class="col-lg-4">
									<label>Coupon Code</label>
									<div class="input-group">
										<input type="text" name="couponCode" id="couponCode"
											value="{{ old('couponCode') }}" class="form-control"
											placeholder="Coupon Code" />
										<div class="input-group-append" onclick="get_coupon()">
											<span class="input-group-text">
												Apply
											</span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-6">
								<h4 class="header-title mt-0 mb-3">
									Bill Details
								</h4>
								<div class="total-payment">
									<table class="table mb-0">
										<tbody>
											<tr>
												<td class="payment-title">Subtotal</td>
												<td>
													<input type="text" value="" class="form-control tsub"
														onkeyup="cal_final_amount()" name="sub_total" readonly />
												</td>
											</tr>
											<tr>
												<td class="payment-title">Discount</td>
												<td><input type="text" value="0" class="form-control tdis"
														onkeyup="cal_final_amount()" name="total_dis" readonly /></td>
											</tr>
											<tr>
												<td class="payment-title">Total</td>
												<td><input type="text" value="0" class="form-control ttotal"
														onkeyup="cal_final_amount()" name="total" readonly /></td>
											</tr>
										</tbody>
									</table>
									<!--end table-->
								</div>
								<!--end total-payment-->
							</div>
							<!--end col-->

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
	$(".select2").select2({
		placeholder: "Select a Name",
		allowClear: true
	});
    $('.date_pick').datepicker({
       format: 'dd-mm-yyyy'
    });
	function get_price(e){
    	var price=$(e).val().split('-')[2];
		if(price)price=price; else price=0;
    	$('.tsub').val(price);
		$('#couponCode').val("");
		$('.tdis').val(0);
		cal_final_amount();
    }
	function get_coupon(){
		if($('.tsub').val() <= 0){
			alert("Coupon cannot apply on 0 price");
			return;
		}
    	$.ajax({
            type: "GET",
            url: "{{route(currentUser().'.getCoupon')}}",
            data: {'couponCode':$('#couponCode').val()},
    		dataType: "json",
            success: function(msg) {
    			if(msg.error){
    				alert(msg.error);
    			}else{
					if(parseFloat(msg.dis)>0)
						var discount=($('.tsub').val()*(parseFloat(msg.dis)/100));
					
						$('.tdis').val(discount.toFixed(2));
						cal_final_amount()
    			}
            }
        });
    }
	function cal_final_amount(){
	var tsub=parseFloat($('.tsub').val());
	var tdis=parseFloat($('.tdis').val());
	
	if(tsub)tsub=tsub; else tsub=0;
	if(tdis)tdis=tdis; else tdis=0;
	
	var total= (tsub-tdis);
	$('.ttotal').val(total.toFixed(2));
}
</script>
@endpush