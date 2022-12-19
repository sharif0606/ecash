@extends('layout.admin.admin_master')
@section('title', 'Supplier List')
@section('content')
<div class="content-wrapper">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">Dashboard</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a>
							</li>
							<li class="breadcrumb-item"><a href="#">Supplier</a></li>
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
						<form method="GET" action="{{route(currentUser().'.allSupplier')}}" class="kt-form kt-form--fit mb-15">
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
								<div class="col-lg-3 mb-lg-0 mb-6">
									<label>Purchase No:</label>
									<input type="text" class="form-control invoice" placeholder="Purchase No" name="invoice"/>
								</div>
								<div class="col-lg-3 pt-2">
									<button class="btn btn-primary btn-primary-icon" id="kt_search">
										<span>
											<i class="la la-search"></i>
											<span>Search</span>
										</span>
									</button>&#160;&#160;
									<a href="{{route(currentUser().'.allSupplier')}}"
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
									@if(count($allSupplier))
									@foreach($allSupplier as $count=>$supplier)
									<tr role="row">
										<td>{{++$count}}</td>
										<td>{{$supplier->name}}</td>
										<td>{{$supplier->custCode}}</td>
										<td>{{$supplier->contact_no_b}}</td>
										<td>{{$supplier->email}}</td>
										<td>@if($supplier->state){{$supplier->state->name}}@endif</td>
										<td>@if($supplier->zone){{$supplier->zone->name}}@endif</td>
										<td>{{$supplier->address}}</td>
										<td>@if($supplier->user){{$supplier->user->name}}<br>{{$supplier->user->mobileNumber}}@endif</td>
										<td>
											<div class="d-flex flex-column">
												@if($supplier->status == 1)
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
														href="{{route(currentUser().'.editSupplier',[encryptor('encrypt', $supplier->id)])}}">
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
													@if($supplier->purchase->count() <= 0 )
													<a class="dropdown-item" onclick="return confirm('Are you sure to delete?')"
														href="{{route(currentUser().'.deleteSupplier', [encryptor('encrypt', $supplier->id)])}}">
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
							{{$allSupplier->links()}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	@endsection
	@push('scripts')
	<script>
		
		
	</script>
	@endpush