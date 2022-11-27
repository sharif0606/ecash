@extends('layout.admin.admin_master')
@section('title', 'Request Package')
@section('content')
<div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Dashboard</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a></li>
				<li class="breadcrumb-item"><a href="#">Shop Managment</a></li>
				<li class="breadcrumb-item active">Request Package</li>
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
<!--begin::Card-->
	<div class="content-body">
		<!-- Responsive tables start -->
		<div class="row" id="table-responsive">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">
							All Package List Here...
						</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
							    <table id="myTable" class="table table-striped text-center table-bordered dt-responsive dataTable">
								    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Number of Invoice</th>
                                            <th>Request to buy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($allPackages))
                                            @foreach($allPackages as $package)
                                                <tr>
                                                    <td>{{$package->name}}</td>
                                                    <td>{{$package->price}}</td>
                                                    <td>{{$package->duration}}</td>
                                                    <td>
                                                        <a onclick="apply_package('{{route('owner.sendPackageRequest',[ encryptor('encrypt', $package->id)])}}')" data-bs-toggle="modal" data-bs-target="#add-new-package-sidebar" href="#" class="btn btn-info" title="Request For Package"><i data-feather="download"></i> Request Now</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table><!--end /table-->
                            </div><!--end /tableresponsive-->
                            <div class="d-flex align-items-center justify-content-between">
                                {{$allPackages->links()}}
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!-- end col -->
            </div><!--end row-->    
        </div><!-- container -->
    </div>
</div>

<!-- Add New Customer Sidebar -->
	<div class="modal modal-slide-in fade" id="add-new-package-sidebar" aria-hidden="true">
		<div class="modal-dialog sidebar-lg">
			<div class="modal-content p-0">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				<div class="modal-header mb-1">
					<h5 class="modal-title">
						<span class="align-middle">Request Form</span>
					</h5>
				</div>
				<div class="modal-body flex-grow-1">
					<form method="get" id="packageForm">
						<div class="mb-1">
							<label for="customer-name" class="form-label">Branch</label>
							<select name="branchId" id="branchId" class="form-control" required>
							    <option value="">Select Branch</option>
							        @if($allBranch)
							            @foreach($allBranch as $b)
							                <option value="{{$b->id}}">{{$b->branch_name}}</option>
							            @endforeach
							        @endif
							</select>
						</div>
						<div class="mb-1">
							<label for="custCode" class="form-label">Do you have any coupon code?</label>
							<input type="text" class="form-control" id="coupon" name="coupon" onkeyup="check_coupon(this.value)"/>
							<small class="couponmsg"></small>
						</div>
						
						<div class="mb-1 d-flex flex-wrap mt-2">
							<button type="submit" class="btn btn-primary me-1">Submit</button>
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Add New Customer Sidebar -->
@endsection
@push('scripts')
<script>
    function apply_package(url){
        $('#packageForm').attr('action',url);
    }
    
    function check_coupon(a){
        if(a){
            $.get("{{route('owner.checkCoupon')}}?coupon="+a, function(data, status){
                if(data == false){
                    $('.couponmsg').html('<b class="text-danger">this coupon code is not valid.</b>');
                }else{
                    $('.couponmsg').html('<b class="text-success">'+data+'</b>');
                }
            });
        }
    }
    
</script>

@endpush