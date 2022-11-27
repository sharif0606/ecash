@extends('layout.admin.admin_master')
@section('title', 'User Package List')
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
				<li class="breadcrumb-item"><a href="#">User Package</a></li>
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
	
	
	
<!--begin::Card-->
	<div class="content-body">
		<!-- Responsive tables start -->
		<div class="row" id="table-responsive">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">
							All Coupon List Here...
						</h4>
						<div class="d-flex justify-content-end">
							<a href="{{route(currentUser().'.addNewUserPackageForm')}}" class="btn btn-primary font-weight-bolder  waves-effect waves-float waves-light">
								<i class="la la-list"></i>New Package</a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
							    <table id="myTable" class="table table-striped text-center table-bordered dt-responsive dataTable">
								    <thead class="thead-light">
                        				<tr>
                        					<th>Company</th>
                        					<th>Package Name</th>
                        					<th>Price</th>
                        					<th>Discount</th>
                        					<th>Coupon Code</th>
                        					<th>Start At</th>
                        					<th>End At</th>
                        					<th>Approved By</th>
                        					<th>Status</th>
                        					<th>Action</th>
                        				</tr>
                        			</thead>
                        			<tbody>
                        				@if(count($allUserPackage))
                        				@foreach($allUserPackage as $up)
                        				<tr>
                        					<td>{{$up->company->company_name}}</td>
                        					<td>{{$up->package->name}}</td>
                        					<td>{{$up->price}}</td>
                        					<td>{{$up->discount}}</td>
                        					<td>{{$up->couponCode}}</td>
                        					<td>
                        						@if($up->startAt)
                        						{{date('d-m-Y',strtotime($up->startAt))}}
                        						@endif
                        					</td>
                        					<td>
                        						@if($up->endAt)
                        						{{date('d-m-Y',strtotime($up->endAt))}}
                        						@endif
                        					</td>
                        					<td>{{$up->user->role->type}}: {{$up->user->name }}</td>
                        					<td>
                        						@if($up->status == 1)
                        						<span class="badge badge-soft-success">Active</span>
                        						@else
                        						<span class="badge badge-soft-danger">Inactive</span>
                        						@endif
                        					</td>
                        					<td>
                        						<a href="{{route(currentUser().'.deleteUserPackage', [encryptor('encrypt', $up->id)])}}"><i
                        								class="fas fa-trash-alt text-danger font-16"></i></a>
                        					</td>
                        				</tr>
                        				@endforeach
                        				@endif
                        			</tbody>
                        			{{$allUserPackage->links()}}
                        		</table>
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