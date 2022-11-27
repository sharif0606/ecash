@extends('layout.admin.admin_master')
@section('title', 'Pending Request Package List')
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
				<li class="breadcrumb-item active">Pending Request Package</li>
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
							Pending Package Request List Here...
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
                                            <th>No Of Bill</th>
                                            <th>Coupon</th>
                                            <th>Requested At</th>
                                            <th>Requested By</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($allPackages))
                                            @foreach($allPackages as $package)
                                                <tr>
                                                    <td>{{$package->package->name}}</td>
                                                    <td>{{$package->price}}</td>
                                                    <td>{{$package->noofbill}}</td>
                                                    <td>{{$package->couponCode}}</td>
                                                    <td>{{date('d M,Y h:iA',strtotime($package->created_at))}}</td>
                                                    <td>{{$package->requested->name}} / {{  $package->requested->mobileNumber}} </td>
													<td><span class="btn btn-danger" title="Pending">Pending</span></td>
                                                    <td>
													    <a href="{{route('superadmin.packageStatus', ['1',encryptor('encrypt', $package->id)])}}" onclick="return confirm('Are you sure to accept this?')" class="btn btn-success">Accept</a>
													    <a href="{{route('superadmin.cancelPackage', [encryptor('encrypt', $package->id)])}}" onclick="return confirm('Are you sure to delete this?')" class="btn btn-danger">Cancel</a>
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
@endsection