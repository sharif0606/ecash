@extends('layout.admin.admin_master')
	@section('title', 'Bill Return List')
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
								<li class="breadcrumb-item"><a href="#">Bill Return</a></li>
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
								All Bill Return List Here...
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
											@if($bill->status!=3 && $bill->status!=2 && $bill->status!=4)
											<!--<li><a class="dropdown-item <?= $bill->status==3?"active":"" ?>" href="#">Partial Return</a></li>-->
											@endif
										</ul>
									@endif
								</div>
									@if($bill->status!=1)
										<small>Reason: <span class="re{{encryptor('encrypt',$bill->id) }}">{{ $bill->cancel_reason }} </span></small>
									@endif
							</td>
							<td>
                                <a class="btn btn-info" href="{{route(currentUser().'.billShow',[encryptor('encrypt', $bill->id)])}}">
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