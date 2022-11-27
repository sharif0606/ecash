@extends('layout.admin.admin_master')
@section('title', 'Product List')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Product List</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Product</a></li>
				<li class="breadcrumb-item active">List</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="content-header-right col-md-3 col-6 mb-2">
		<!--begin::Button-->
		<a href="{{route(currentUser().'.addNewProductForm')}}" class="btn btn-primary font-weight-bolder">
			<span class="svg-icon svg-icon-md">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<rect x="0" y="0" width="24" height="24" />
						<circle fill="#000000" cx="9" cy="15" r="6" />
						<path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>New Product</a>
			<!--end::Button-->
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
	
	<div class="col-lg-12 col-12">
		<div class="card card-company-table">
		  <div class="card-body p-0">
			 
			<div class="table-responsive">
			<?php $type=array('Common','Regular','Very Regular')?>
			  <table class="table">
				<thead class="table-dark">
					<tr>
						<th>Name</th>
						<th>Brand</th>
						<th>Type/Category</th>
						<th>Status</th>
						<th>Edit</th>
					</tr>
				</thead>
					<tbody>
						@if(count($allProduct))
							@foreach($allProduct as $index => $product)
							<tr>
								<td class="text-nowrap">
									<div class="d-flex flex-column">
									  <span class="fw-bolder mb-25">{{$product->name}}</span>
									</div>
								</td>
								<td class="text-nowrap">
									<div class="d-flex flex-column">
									  <span class="fw-bolder mb-25">{{$product->brand->name}}</span>
									</div>
								</td>
								<td class="text-nowrap">
									<div class="d-flex flex-column">
									  <span class="fw-bolder mb-25">{{$product->categories->name}}</span>
									</div>
								</td>
								<td class="text-nowrap">
									<div class="d-flex flex-column">
										@if($product->status == 1)
										<span class="badge rounded-pill badge-light-primary me-1">Active</span>
										@else
										<span class="badge rounded-pill badge-light-danger me-1">Inactive</span>
										@endif
									</div>
								</td>
								<td>
									<div class="dropdown">
										<button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown" aria-expanded="false">
										<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
										</button>
										<div class="dropdown-menu" style="">
										<a class="dropdown-item" href="{{route(currentUser().'.editProduct',[encryptor('encrypt', $product->id)])}}">
											<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
											<span>Edit</span>
										</a>
										<!--<a class="dropdown-item" href="{{route(currentUser().'.deleteProduct', [encryptor('encrypt', $product->id)])}}">
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
				<div class="d-flex align-items-center justify-content-between">
					
				</div>
				<!--end: Datatable-->
			</div>
		</div>
	</div>
</div>
	<!--end::Card-->
@endsection

@push('scripts')
<!--begin::Page Vendors(used by this page)-->
		<script src="{{asset('/')}}assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.4"></script>
		<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
	<script src="{{asset('/')}}assets/js/pages/crud/datatables/extensions/buttons.js?v=7.0.4"></script>
<!--end::Page Scripts-->

@endpush