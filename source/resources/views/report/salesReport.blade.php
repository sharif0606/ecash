@extends('layout.admin.admin_master')
@section('title', 'Sales Report List')
@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('/')}}assets/vendors/css/forms/select/select2.min.css">
@endpush
@section('content')
<style>
	thead tr th {
  position: sticky;
  top: 0;
}
tfoot tr th {
  position: sticky;
  bottom: 0;
}
</style>

<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">Sales Report List</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a></li>
							<li class="breadcrumb-item"><a href="#">Report</a></li>
							<li class="breadcrumb-item active">Sales</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="content-body">
		<!-- Responsive tables start -->
		<div class="row" id="table-responsive">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Sales Report</h4>
					</div>
					<div class="card-body">
						<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
							<div class="row">
								<div class="col-12">
									<!--begin: Search Form-->
									<form method="GET" action="{{route(currentUser().'.allSalesReport')}}" class="kt-form kt-form--fit mb-15">
										<div class="row mb-1 ">
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>From Date:</label>
												<input type="date" class="form-control fromdate" name="fromdate"/>
											</div>
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>To Date:</label>
												<input type="date" class="form-control todate" name="todate"/>
											</div>
											@php
												$term=array("All","Cash", "Bank Card", "Bank Cheque", "Mobile Bank");
											@endphp
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>Bill Terms :</label>
												<select name="sales_term" class="form-control saleType">
													@foreach($term as $k=>$v)
													<option value="{{$k}}" @if(isset($_GET['sales_term']) && $k==$_GET['sales_term'])
														selected @endif>{{$v}}</option>
													@endforeach
												</select>
											</div>
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>Customer:</label>
												<select class="form-control customer" name="customer" data-col-index="2">
													<option value="">Select Customer</option>
													@if(count($allCustomer) > 0)
														@foreach($allCustomer as $cust)
														<option value="{{ $cust->id }}" @if(isset($_GET['customer']) && $cust->
															id==$_GET['customer']) selected @endif >{{ $cust->name }}</option>
														@endforeach
													@endif
												</select>
											</div>
											
											<div class="col-lg-3 pt-2">
												<button class="btn btn-primary btn-primary-icon" id="kt_search">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
												</button>&#160;&#160;
												<a href="{{route(currentUser().'.allSalesReport')}}"
													class="btn btn-secondary btn-secondary--icon" id="kt_reset">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
												</a>
											</div>
										</div>
									</form>
									@php $totalAmt=$totalDue=0; @endphp
									<div class="table-responsive" style="max-height:400px; overflow:auto">
										<table class="table table-bordered table-hover table-checkable" id="kt_datatable">
											<thead class="thead-light">
												<tr>
													<th>#SL</th>
													<th>Supplier</th>
													<th>Ref No</th>
													<th>P Date</th>
													<th>P Term</th>
													<th>Due</th>
													<th>Amount</th>
												</tr>
											</thead>
											
											<tbody>
												@if(count($salesReport))
													@foreach($salesReport as $index => $pr)
														@php 
															$totalAmt+=$pr->total_amount;
															$totalDue+=$pr->total_due;
														@endphp	
														<tr>
															<td>{{$index + 1}}</td>
															<td>{{$pr->customer->name}} <br/> {{$pr->customer->custCode}}</td>
															<td>{{$pr->ref_no}}</td>
															<td>{{$pr->purchase_date}}</td>
															<td>{{$term[$pr->bill_term]}}</td>
															<td>{{$pr->total_due}}</td>
															<td>{{$pr->total_amount}}</td>
														</tr>
													@endforeach
												@endif
											</tbody>
											<tfoot>
												<tr>
													<th colspan="5">Total</th>
													<th>{{$totalDue}}</th>
													<th>{{$totalAmt}}</th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- Responsive tables end -->
</div>


@endsection

@push('scripts')
<script src="{{asset('/')}}assets/vendors/js/forms/select/select2.full.min.js"></script>
<script>
	// predefined ranges
	@if(isset($_GET['fromdate']))
		$('.fromdate').val("{{$_GET['fromdate']}}");
		$('.todate').val("{{$_GET['todate']}}");
	@endif

	//product select search init
	$('.product').select2({
		placeholder: 'Select Product'
	});
 
</script>

@endpush