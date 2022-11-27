@extends('layout.admin.admin_master')
	@section('title', 'Bill List')
	@section('content')
	<div class="content-wrapper">
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
								<li class="breadcrumb-item"><a href="#">Bill</a></li>
								<li class="breadcrumb-item active">List</li>
							</ol>
						</div>
					</div>
				</div>
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
		<div class="content-body">
			<!-- Responsive tables start -->
			<div class="row" id="table-responsive">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">
								All Bill List Here...
							</h4>
							<div class="d-flex justify-content-end">
								<a href="{{route(currentUser().'.addNewBillForm')}}"
									class="btn btn-primary font-weight-bolder  waves-effect waves-float waves-light">
									<i class="la la-list"></i>New Bill</a>
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
														<th>Bill Details</th>
														<th>Price</th>
														<th>Due</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>

												<tbody>
													@php($count=1)
													@if(count($allBill))
													@foreach($allBill as $bill)
													<tr role="row">
														<td>
															{{$count++}}
														</td>
														<td>
															<div
																class="d-flex align-items-start flex-column text-start">
																<div><span class="fw-bolder">Bill No:</span>{{$bill->bill_no}}<span>
																		</div>
																<div><span class="fw-bolder">Invoice Date:</span><span>
																		{{ date('M d, Y',strtotime($bill->bill_date))}}</div>
																<div><span class="fw-bolder">Customer Name:</span><span>
																		{{$bill->customer->name}}</div>
																<div><span class="fw-bolder">Customer Code:</span><span>
																		{{$bill->customer->custCode}}</div>
																		
															
															</div>

									
										</td>
										<td>{{$bill->total_amount}}</td>
										<td>{{$bill->total_due}}</td>
										<td class="text-nowrap">
								<div class="d-flex flex-column">
									@if(currentUser() === 'salesman')
										@if($bill->status==1)
											<a class="btn btn-success"> Approved </a>
										@elseif($bill->status==2)
											<a class="btn btn-danger"> Canceled </a>
										@elseif($bill->status==3)
											<a class="btn btn-info"> Partial Return </a>
										@elseif($bill->status==4)
											<a class="btn btn-warning"> Return </a>
										@else
											<a class="btn btn-danger"> Replace </a>
										@endif
									@else
									<div class="btn-group">
										@if($bill->status==1)
											<?php $us="Approved"; $class="success"; ?>
										@elseif($bill->status==2)
											<?php $us="Canceled"; $class="danger"; ?>
										@elseif($bill->status==3)
											<?php $us="Partial Return"; $class="info"; ?>
										@elseif($bill->status==4)
											<?php $us="Return"; $class="warning"; ?>
										@else
											<?php $us="Replace"; $class="danger"; ?>
										@endif

										<button type="button" class="btn btn-{{ $class }} dropdown-toggle" id="btn{{encryptor('encrypt',$bill->id) }}" data-bs-toggle="dropdown" aria-expanded="false">
											{{ $us }}
										</button>
										<ul class="dropdown-menu" aria-labelledby="btn{{encryptor('encrypt',$bill->id) }}">
											@if($bill->status!=1)
											<li><a id="op{{encryptor('encrypt',$bill->id) }}1" class="dropdown-item <?= $bill->status==1?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}" href="#" onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',1,'Approved')">Approved</a></li>
											@endif
											@if($bill->status!=2)
											<li><a id="op{{encryptor('encrypt',$bill->id) }}2" class="dropdown-item <?= $bill->status==2?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}" href="#" onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',2,'Canceled')">Canceled</a></li>
											@endif
											@if($bill->status!=3 && $bill->status!=2 && $bill->status!=4)
											<!--<li><a class="dropdown-item <?= $bill->status==3?"active":"" ?>" href="#">Partial Return</a></li>-->
											@endif
											@if($bill->status!=4 && $bill->status!=2)
											<li><a id="op{{encryptor('encrypt',$bill->id) }}4" class="dropdown-item <?= $bill->status==4?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}" href="#" onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',4,'Return')">Return</a></li>
											@endif
											@if($bill->status!=5 && $bill->status!=2 && $bill->status!=4)
											<li><a class="dropdown-item <?= $bill->status==5?"active":"" ?>" href="{{route(currentUser().'.replaceBillForm',[encryptor('encrypt', $bill->id)])}}">Replace</a></li>
											@endif
										</ul>
									@endif
								</div>
									@if($bill->status!=1)
										<small>Reason: <span class="re{{encryptor('encrypt',$bill->id) }}">{{ $bill->cancel_reason }} </span></small>
									@endif
									@if($bill->to_ref_id)
									<a target="_blank" href="{{route(currentUser().'.billShow',[encryptor('encrypt', $bill->to_ref_id)])}}">Replaced From</a>
									@endif
							</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown" aria-expanded="false">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
									</button>
									<div class="dropdown-menu" style="">
									<a class="dropdown-item" href="{{route(currentUser().'.billShow',[encryptor('encrypt', $bill->id)])}}">
										<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
										<span>Show</span>
									</a>
									<a class="dropdown-item" href="{{route(currentUser().'.edit',[encryptor('encrypt', $bill->id)])}}">
										<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
										<span>Edit</span>
									</a>
									<!--<a class="dropdown-item" href="{{route(currentUser().'.billShow',[encryptor('encrypt', $bill->id)])}}">
										<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
										<span>Delete</span>
									</a>-->
									</div>
								</div>
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
	{{--{{$allBill->links()}}--}}
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
		'url': "@if(currentUser() === 'owner' || currentUser() === 'salesmanager'){{route(currentUser().'.changeStatus')}}@endif",
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