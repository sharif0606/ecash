@extends('layout.admin.admin_master')
@section('title', 'Customer List')
@section('content')
	<div class="content-wrapper">
		<div class="content-header row">
			<div class="content-header-left col-md-9 col-12 mb-2">
				<div class="row breadcrumbs-top">
					<div class="col-12">
						<h2 class="content-header-title float-start mb-0">Customer List</h2>
						<div class="breadcrumb-wrapper">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a
										href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
								<li class="breadcrumb-item"><a href="#">Customer</a></li>
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
								All Customer List Here...
							</h4>
							<div class="d-flex justify-content-end">
								<a href="{{route(currentUser().'.addNewCustomerForm')}}"
									class="btn btn-primary font-weight-bolder  waves-effect waves-float waves-light">
									<i class="la la-list"></i>New Customer</a>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
											<table class="table table-striped text-center table-bordered dt-responsive dataTable">
												<thead>
													<tr>
														<th>SL.</th>
														<th>Name</th>
														<th>Mobile</th>
														<th>Alternate Mobile</th>
														<th>Email</th>
														<th>Division</th>
														<th>District</th>
														<th>Address</th>
														<th>Reference Person</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>

												<tbody>
													@if(count($allCustomer))
													@foreach($allCustomer as $count=>$customer)
													<tr role="row">
													    <td>{{++$count}}</td>
														<td>{{$customer->name}}</td>
														<td>{{$customer->custCode}}</td>
														<td>{{$customer->contact_no_b}}</td>
														<td>{{$customer->email}}</td>
														<td>@if($customer->state){{$customer->state->name}}@endif</td>
														<td>@if($customer->zone){{$customer->zone->name}}@endif</td>
														<td>{{$customer->address}}</td>
														<td>@if($customer->user){{$customer->user->name}}<br>{{$customer->user->mobileNumber}}@endif</td>
														<td>
    														<div class="d-flex flex-column">
                    											@if($customer->status == 1)
                    											<span class="badge rounded-pill badge-light-primary me-1">Active</span>
                    											@else
                    											<span class="badge rounded-pill badge-light-danger me-1">Inactive</span>
                    											@endif
                    										</div>
														</td>
														<td>
														    
															<div class="dropdown">
																<button type="button"
																	class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light"
																	data-bs-toggle="dropdown" aria-expanded="false">
																	<svg xmlns="http://www.w3.org/2000/svg"
																		width="14" height="14" viewBox="0 0 24 24"
																		fill="none" stroke="currentColor"
																		stroke-width="2" stroke-linecap="round"
																		stroke-linejoin="round"
																		class="feather feather-more-vertical">
																		<circle cx="12" cy="12" r="1"></circle>
																		<circle cx="12" cy="5" r="1"></circle>
																		<circle cx="12" cy="19" r="1"></circle>
																	</svg>
																</button>
																<div class="dropdown-menu" style="">
																	<a class="dropdown-item"
																		href="{{route(currentUser().'.editCustomer',[encryptor('encrypt', $customer->id)])}}">
																		<svg xmlns="http://www.w3.org/2000/svg"
																			width="14" height="14"
																			viewBox="0 0 24 24" fill="none"
																			stroke="currentColor" stroke-width="2"
																			stroke-linecap="round"
																			stroke-linejoin="round"
																			class="feather feather-edit-2 me-50">
																			<path
																				d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
																			</path>
																		</svg>
																		<span>Edit</span>
																	</a>
																	@if($customer->sell->count() <= 0 )
																	<a class="dropdown-item" onclick="return confirm('Are you sure to delete?')"
																		href="{{route(currentUser().'.deleteCustomer', [encryptor('encrypt', $customer->id)])}}">
																		<svg xmlns="http://www.w3.org/2000/svg"
																			width="14" height="14"
																			viewBox="0 0 24 24" fill="none"
																			stroke="currentColor" stroke-width="2"
																			stroke-linecap="round"
																			stroke-linejoin="round"
																			class="feather feather-trash me-50">
																			<polyline points="3 6 5 6 21 6">
																			</polyline>
																			<path
																				d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
																			</path>
																		</svg>
																		<span>Delete</span>
																	</a>
																	@endif
																</div>
															</div>
														</td>
													</tr>
													@endforeach
													@endif
												</tbody>
											</table>
											{{$allCustomer->links()}}
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
		
	</div>
@endsection
