@extends('layout.admin.admin_master')

@section('title', 'Bill List')
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
<!--begin::Card-->
<div class="card card-custom">
	<div class="card-header flex-wrap border-0 pt-6 pb-0">
		<div class="card-title">
			<span class="d-block text-muted pt-2 font-size-sm"></span></h3>
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Customer</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Payment</a>
				</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>

	</div>
	<div class="card-body">
		<!--begin: Datatable-->
		<form
			action="@if(currentUser() === 'owner' || currentUser() === 'salesmanager' || currentUser() === 'salesman') {{route(currentUser().'.searchDueCustomer')}} @endif"
			method="POST" id="searchDueCustomer">
			@csrf
			<div class="d-flex">
				<div class="form-group w-100 m-0">
					<input autocomplete="on" type="text" name="customerNumber" class="form-control"
						placeholder="Search customer by contact number">
				</div>
				<button type="submit" class="btn btn-primary ml-3">Search</button>
			</div>
		</form>
		<div id="dueBillResult" class="my-3"></div>
		<div id="duePayForm" class="d-none my-3">
			<ul class="d-flex align-center justify-content-between customer-summary mb-3 p-3">
				<div class="form-group w-100 m-0">
					<input autocomplete="off" value="0" type="text" name="payAmount" class="form-control"
						placeholder="Input amount you want to pay">
				</div>
				<button type="button" id="payDue" class="btn btn-success ml-3">Pay</button>
				<button type="button" id="cancelPayDue" class="btn btn-danger ml-3">Cancel</button>
			</ul>
		</div>
	</div>
	<!--end card-body-->
</div>
<!--end card-->

@endsection

@push('scripts')
<script>
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$(document).ready(function(){
		$('form#searchDueCustomer').on('submit', function(e){
			e.preventDefault();
			$("#duePayForm").addClass('d-none')
			$form = $(this);
			let customerNumber = $('input[name=customerNumber]').val();
			if(customerNumber == '' || customerNumber == null) alert('Please input customer number');
			else if(isNaN(customerNumber)) alert('Input must be mobile number')
			else {
				getSearchResult();
			}
		})

		$(document).on('click', 'button#payDueForm', function(){
			$("#duePayForm").removeClass('d-none');
			let totalDue = $(this).attr('data-totalDue');
			$('input[name=payAmount]').val(totalDue);
		})

		$('#cancelPayDue').on('click', function(){
			$("#duePayForm").addClass('d-none')
			$('input[name=payAmount]').val(0);
		})
		$('#payDue').on('click', function(){
			let customerId = $('#payDueForm').attr('data-customerId')
			let payAbleAmount = $('input[name=payAmount]').val();
			let totalDue = $('#payDueForm').attr('data-totalDue')
			if(parseFloat(payAbleAmount) == 0.00) return alert('Please input amount');
			// if(parseFloat(payAbleAmount) > parseFloat(totalDue)) return alert('You paid more than the rest of the money. Please check');
			if(customerId != '' && parseFloat(payAbleAmount) != 0.00){
				$.ajax({
					url: "{{route('owner.payDue')}}",
					type:"POST",
					data: {
						customerId: customerId,
						payAbleAmount: payAbleAmount
					},
					success: function(response){ // What to do if we succeed
						if(response.status) {
							$("#duePayForm").addClass('d-none')
							$('input[name=payAmount]').val("1");
							getSearchResult();
						}else{
							console.log(response)
						}
					},
					error: function(response){
						console.log('Error'+response);
					}
				});
			}
		})

	})

	function getSearchResult(){
		$.ajax({
			url: $form.attr('action'),
			type:"POST",
			data: $form.serialize(),
			success: function(response){ // What to do if we succeed
				$('#dueBillResult').html(response.data)
			},
			error: function(response){
				console.log('Error'+response);
			}
		});
	}
</script>
@endpush