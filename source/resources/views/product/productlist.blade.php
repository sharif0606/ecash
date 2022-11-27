@extends('layout.admin.admin_master')
@section('title', 'Product List')
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
<div class="content-wrapper container-xxl p-0">
		<div class="content-header row">
			<div class="content-header-left col-md-9 col-12 mb-2">
				<div class="row breadcrumbs-top">
					<div class="col-12">
						<h2 class="content-header-title float-start mb-0">Dashboard</h2>
						<div class="breadcrumb-wrapper">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a></li>
								<li class="breadcrumb-item"><a href="#">Import Product</a></li>
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
								All Product List Here to import...
							</h4>
							<div class="d-flex justify-content-end">
								<a href="{{route(currentUser().'.addNewProductForm')}}"
									class="btn btn-primary font-weight-bolder  waves-effect waves-float waves-light">
									<i class="la la-list"></i>New Product</a>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
                                    		<!--begin: Search Form-->
                                    		<form method="GET" action="{{route(currentUser().'.importProductList')}}" class="kt-form kt-form--fit mb-15">
                                    			<div class="row mb-2">
                                    				<div class="col-lg-3 mb-lg-0 mb-6">
                                    					<label>Brand:</label>
                                    					<select class="form-control datatable-input" name="brands" data-col-index="2">
                                    					<option value="">Select Brand</option>
                                    						@if(count($allBrand) > 0)
                                    							@foreach($allBrand as $brand)
                                    								<option value="{{ $brand->id }}" @if(Session::get('brands')== $brand->id) selected @endif>{{ $brand->name }}</option>
                                    							@endforeach
                                    						@endif
                                    					</select>
                                    				</div>
                                    				<div class="col-lg-3 mb-lg-0 mb-6">
                                    					<label>Category:</label>
                                    					<select class="form-control datatable-input" name="category" data-col-index="4">
                                    						<option value="">Select Category</option>
                                    						@if(count($allCategory) > 0)
                                    							@foreach($allCategory as $c)
                                    							<option value="{{ $c->id }}" @if(Session::get('category')== $c->id) selected @endif>{{ $c->name }}</option>
                                    							@endforeach
                                    						@endif
                                    					</select>
                                    				</div>
                                    				<div class="col-lg-3 mb-lg-0 mb-6">
                                    					<label>Name:</label>
                                    					<input type="text" class="form-control" name="name" value="@if(Session::get('name')){{Session::get('name')}}@endif">
                                    				</div>
                                    				<div class="col-lg-3 mb-lg-0 mb-6 pt-2">
                                    					<button class="btn btn-primary btn-primary--icon" id="kt_search">
                                        					<span>
                                        						<i class="la la-search"></i>
                                        						<span>Search</span>
                                        					</span>
                                        				</button>&#160;&#160;
                                        				<a href="{{route(currentUser().'.importProductList')}}?fresh=1" class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                        					<span>
                                        						<i class="la la-close"></i>
                                        						<span>Reset</span>
                                        					</span>
                                        				</a>
                                    				</div>
                                    			</div>
                                    		</form>
                                    		<!--begin: Datatable-->
											<table id="myTable" class="table table-striped text-center table-bordered dt-responsive dataTable">
                                    			<thead class="thead-light">
                                    				<tr>
                                    					<th>Sr</th>
                                    					<th>Name</th>
                                    					<th>Brand</th>
                                    					<th>Category</th>
                                    					<th>Serial No</th>
                                    					<th>Status</th>
                                    					<th>Action</th>
                                    				</tr>
                                    			</thead>
                                    			<tbody>
                                    				@if(count($productlist))
                                    					@foreach($productlist as $index => $product)
                                    					<tr id="{{$index + 1}}">
                                    						<td>{{$index + 1}}</td>
                                    						<td>{{$product->name}}</td>
                                    						<td>{{$product->brand->name}}</td>
                                    						<td>{{$product->categories->name}}</td>
                                    						<td>{{$product->serialNo}}</td>
                                    						<td>
                                    							@if($product->status == 1)
                                    								<span class="badge badge-soft-success">Active</span>
                                    							@else
                                    								<span class="badge badge-soft-danger">Inactive</span>
                                    							@endif
                                    						</td>
                                    						<td>
                                    							<a href="javascript:void(0)" id="imp{{$index + 1}}" onclick="import_medi({{$product->id}},'{{$product->name}}',{{$index + 1}})" class="mr-2 btn btn-outline-info"><i class="fas fa-download text-info font-16"></i> Import</a>
                                    							<a href="javascript:void(0)" id="wait{{$index + 1}}" class="mr-2 btn btn-outline-info d-none"><i class="fas fa-circle-notch fa-spin text-info font-16"></i> Waitting...</a>
                                    						</td>
                                    					</tr>
                                    					@endforeach
                                    				@endif
                                    			</tbody>
                                    		</table>
		
                                		<div class="d-flex align-items-center justify-content-between">
                                		{{$productlist->links()}}	
                                		</div>
                                		<!--end: Datatable-->
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
<!--end::Card-->
@endsection

@push('scripts')
<script>
	function import_medi(pid,pname,indx){
		// hide import button and show waitting
		$('#imp'+indx).addClass('d-none')
		$('#wait'+indx).removeClass('d-none')
    		$.ajax({
        		'url': '{{route(currentUser().'.importProduct')}}'+'?pid='+pid,
        		'type': 'GET',
        		'data': {},
        		success: function(response){ // What to do if we succeed
        		
        			if(response){
        				alert(response);
        				$('#imp'+indx).removeClass('d-none')
        		        $('#wait'+indx).addClass('d-none')
        			}else{
        			    $('#'+indx).addClass('d-none')
        			}
        		},
        		error: function(response){
        			console.log(response);
        		}
        	});
	}
</script>
@endpush