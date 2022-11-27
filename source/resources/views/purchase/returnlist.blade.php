@extends('layout.admin.admin_master')
	@section('title', 'Purchase Return List')
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
								<li class="breadcrumb-item"><a href="#">Purchase Return</a></li>
								<li class="breadcrumb-item active">List</li>
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
								All Purchase Return List Here...
							</h4>
							<div class="d-flex justify-content-end">

								<a href="{{route(currentUser().'.addNewPurchaseForm')}}"
									class="btn btn-primary font-weight-bolder  waves-effect waves-float waves-light">
									<i class="la la-list"></i>New Purchase</a>


							</div>


						</div>
						<div class="card-body">
							<div class="table-responsive">
								<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
											<table id="myTable"
												class="table table-striped text-center table-bordered dt-responsive dataTable">

												<thead>
													<tr>
														<th>SL.</th>
														<th>Purchase Details</th>
														<th>Supplier Details</th>
														<th>Discount|Tax</th>
														<th>Price</th>
														<th>Due</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>

												<tbody>
													@php($count=1)
													@if(count($allPurchase))
													@foreach($allPurchase as $purchase)
													<tr role="row">
														<td>
															{{$count++}}
														</td>
														<td>
															<div
																class="d-flex align-items-start flex-column text-start">
																<div><span class="fw-bolder">Purchase No:</span>{{$purchase->purchase_no}}<span>
																		</div>
																<div><span class="fw-bolder">Invoice Date:</span><span>
																		{{ date('M d, Y',strtotime($purchase->purchase_date))}}</div>
																
															</div>
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

									
										</td>
										<td>{{$purchase->total_amount}}</td>
										<td>{{$purchase->total_due}}</td>
<td class="text-nowrap">
								<div class="d-flex flex-column">
									@if(currentUser() === 'salesman')
										@if($purchase->status==1)
											<a class="btn btn-success"> Approved </a>
										@elseif($purchase->status==2)
											<a class="btn btn-danger"> Canceled </a>
										@elseif($purchase->status==3)
											<a class="btn btn-info"> Partial Return </a>
										@elseif($purchase->status==4)
											<a class="btn btn-warning"> Return </a>
										@else
											<a class="btn btn-danger"> Replace </a>
										@endif
									@else
									<div class="dropdown">
										@if($purchase->status==1)
											<?php $us="Approved"; $class="success"; ?>
										@elseif($purchase->status==2)
											<?php $us="Canceled"; $class="danger"; ?>
										@elseif($purchase->status==3)
											<?php $us="Partial Return"; $class="info"; ?>
										@elseif($purchase->status==4)
											<?php $us="Return"; $class="warning"; ?>
										@else
											<?php $us="Replace"; $class="danger"; ?>
										@endif
	
											<button type="button" class="btn btn-{{ $class }} dropdown-toggle" id="btn{{encryptor('encrypt',$purchase->id) }}" data-bs-toggle="dropdown" aria-expanded="false">
												{{ $us }}
											</button>
											<ul class="dropdown-menu" aria-labelledby="btn{{encryptor('encrypt',$purchase->id) }}">
											@if($purchase->status!=1)
											<li><a id="op{{encryptor('encrypt',$purchase->id) }}1" class="dropdown-item <?= $purchase->status==1?"active":"" ?> op{{encryptor('encrypt',$purchase->id) }}" href="#" onclick="changeStatus('{{encryptor('encrypt',$purchase->id) }}',1,'Approved')">Approved</a></li>
											@endif
											@if($purchase->status!=3 && $purchase->status!=2 && $purchase->status!=4)
											{{--<a id="op{{encryptor('encrypt',$purchase->id) }}3" class="dropdown-item <?= $purchase->status==3?"active":"" ?> op{{encryptor('encrypt',$purchase->id) }}" href="#" onclick="changeStatus('{{encryptor('encrypt',$purchase->id) }}',3,'Partial Return')">Partial Return</a>--}}
											@endif
										</ul>
									</div>
									@endif
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Responsive tables end -->
	</div>
	{{--{{$allPurchase->links()}}--}}
	</div>
	@endsection
	@push('scripts')
	<script>
		$(document).ready(function () {
			$('#myTable').DataTable();
		});
		function changeStatus(bid,status,ans) {
	
	if(status!=1)
		var reason=window.prompt("Reason of activity","");
	else
		var reason="";
	
	$.ajax({
		'url': "@if(currentUser() === 'owner' || currentUser() === 'salesmanager'){{route(currentUser().'.changePurchaseStatus')}}@endif",
		'type': 'GET',
		'data': {bid:bid,status:status,reason:reason},
		success: function(response){ // What to do if we succeed
			if(response == "success")
				location.reload();
			else
				alert(response);
		},
		error: function(response){
			console.log('Error'+response);
		}
	});
}
	</script>
	@endpush