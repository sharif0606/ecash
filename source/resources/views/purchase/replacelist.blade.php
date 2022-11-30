@extends('layout.admin.admin_master')
@section('title', 'Purchase Replace List')
@section('content')
<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">Dashboard</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a
									href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a>
							</li>
							<li class="breadcrumb-item"><a href="#">Purchase Replace</a></li>
							<li class="breadcrumb-item active">List</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="content-body">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<!--begin: Search Form-->
						<form method="GET" action="{{route(currentUser().'.allPurchaseReplace')}}" class="kt-form kt-form--fit mb-15">
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
									<label>Supplier:</label>
									<input type="text" class="form-control supplier_contact" placeholder="Contact Number" name="supplier_contact"/>
								</div>
								<div class="col-lg-3 pt-2">
									<button class="btn btn-primary btn-primary-icon" id="kt_search">
										<span>
											<i class="la la-search"></i>
											<span>Search</span>
										</span>
									</button>&#160;&#160;
									<a href="{{route(currentUser().'.allPurchaseReplace')}}"
										class="btn btn-secondary btn-secondary-icon" id="kt_reset">
										<span>
											<i class="la la-close"></i>
											<span>Reset</span>
										</span>
									</a>
								</div>
							</div>
						</form>
						<div class="table-responsive">
							<table class="table table-striped text-center table-bordered">
								<thead>
									<tr>
										<th>Purchase Details</th>
										<th>Supplier Details</th>
										<th>Price</th>
										<th>Due</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody>
									@if(count($allPurchase))
									@foreach($allPurchase as $purchase)
									<tr role="row">
										<td>
											<div
												class="d-flex align-items-start flex-column text-start">
												<div><span class="fw-bolder">Purchase No:</span>{{$purchase->purchase_no}}</div>
												<div><span class="fw-bolder">Invoice Date:</span>{{ date('M d, Y',strtotime($purchase->purchase_date))}}</div>
											</div>
										</td>
										<td class="text-nowrap">
											<div class="d-flex flex-column">
												<span class="fw-bolder mb-25">{{$purchase->supplier->name}}</span>
												<span class="font-small-2 text-muted">{{$purchase->supplier->address}}</span>
												<span class="font-small-2 text-muted">{{$purchase->supplier->supCode}}</span>
											</div>
										</td>
						
										<td class="text-nowrap">
											<div class="d-flex flex-column">
												<span class="fw-bolder">T: {{$purchase->total_tax}}</span>
												<span class="fw-bolder">D: {{$purchase->total_dis}}</span>
											</div>
										</td>
										<td>{{$purchase->total_amount}}</td>
										<td>{{$purchase->total_due}}</td>
										<td class="text-nowrap">
											<div class="d-flex flex-column">
												<a target="_blank" href="{{route(currentUser().'.purchaseShow',[encryptor('encrypt', $purchase->bill_reff)])}}" class="btn btn-danger" >
													Replace to
												</a>
												<span class="font-small-2">Reason: {{$purchase->cancel_reason}}</span>
											</div>
										</td>
										<td>
											<a class="btn btn-info" href="{{route(currentUser().'.purchaseShow',[encryptor('encrypt', $purchase->id)])}}">
												<span>Show</span>
											</a>
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
						</div>
						<div class="text-center">{{$allPurchase->links()}}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
	$(document).ready(function () {
		$('#myTable').DataTable();
	});
</script>
@endpush