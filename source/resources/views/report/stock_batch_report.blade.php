@extends('layout.admin.admin_master')
@section('title', 'Batch Wise Stock Report List')
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
<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">Batch Wise Stock Report List</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a></li>
							<li class="breadcrumb-item"><a href="#">Report</a></li>
							<li class="breadcrumb-item active">Stock</li>
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
						<h4 class="card-title">
							Batch Wise Stock Report
						</h4>
					</div>
					<div class="card-body">
						<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
							<div class="row">
								<div class="col-12">
									<!--begin: Search Form-->
									<form method="GET" action="{{route(currentUser().'.StockBatch')}}" class="kt-form kt-form--fit mb-15">
										<div class="row mb-1 ">
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>From Date:</label>
												<input type="date" class="form-control fromdate" name="fromdate"/>
											</div>
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>To Date:</label>
												<input type="date" class="form-control todate" name="todate"/>
											</div>
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>Category:</label>
												<select class="form-control category" name="category" data-col-index="2">
													<option value="">Select Category</option>
													@if(count($allCategory) > 0)
														@foreach($allCategory as $cat)
														<option value="{{ $cat->id }}" @if(isset($_GET['category']) && $cat->
															id==$_GET['category']) selected @endif >{{ $cat->name }}</option>
														@endforeach
													@endif
												</select>
											</div>
											<div class="col-lg-3 mb-lg-0 mb-6">
												<label>Product:</label>
												<select class="form-control product" name="product" data-col-index="2">
													<option value="">Select Product</option>
													@if(count($allProduct) > 0)
														@foreach($allProduct as $product)
														<option value="{{ $product->id }}" @if(isset($_GET['product']) && $product->
															id==$_GET['product']) selected @endif >{{ $product->name }}</option>
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
												<a href="{{route(currentUser().'.StockBatch')}}"
													class="btn btn-secondary btn-secondary--icon" id="kt_reset">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
												</a>
											</div>
										</div>
									</form>
									@php $totalSt=$totalSp=$totalBp=$totalStp=0; @endphp
									<div class="table-responsive" style="max-height:400px; overflow:auto">
										<table class="table table-bordered table-hover table-checkable" id="kt_datatable">
											<thead class="thead-light">
												<tr>
													<th>#SL</th>
													<th>Name</th>
													<th>BatchId</th>
													<th>Stock</th>
													<th>Sell Price</th>
													<th>Buy Price</th>
													<th>Stock Price</th>
												</tr>
											</thead>
											
											<tbody>
												@if(count($batchWiseReport))
													@foreach($batchWiseReport as $index => $batchReport)
														@php
															$totalSt+=$batchReport->stock;
															$totalSp+=$batchReport->stock * $batchReport->sellPrice;
															$totalBp+=$batchReport->stock * $batchReport->buyPrice;
															$totalStp+=$batchReport->stock * $batchReport->buyPrice;
														@endphp
														<tr>
															<td>{{$index + 1}}</td>
															<td>{{$batchReport->name}}</td>
															<td>{{$batchReport->batchId}}</td>
															<td>{{$batchReport->stock}}</td>
															<td>{{$batchReport->sellPrice}}</td>
															<td>{{$batchReport->buyPrice}}</td>
															<td>{{$batchReport->stock * $batchReport->buyPrice}}</td>
														</tr>
													@endforeach
												@endif
											</tbody>
											<tfoot>
												<tr>
													<th colspan="3">Total</th>
													<th>{{$totalSt}}</th>
													<th>{{$totalSp}}</th>
													<th>{{$totalBp}}</th>
													<th>{{$totalStp}}</th>
												</tr>
											</tfoot>
										</table><!--end: Datatable-->
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