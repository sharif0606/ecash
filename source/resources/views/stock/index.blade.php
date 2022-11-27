@extends('layout.admin.admin_master')
@section('title', 'Stock List')
@section('content')


<!--begin::Card-->
<div class="card card-custom">
	<div class="card-header">
		<div class="card-title">

			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Supplier</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Stock List</a>
				</li>
			</ul>
			{{-- 
			<span class="card-icon">
				<i class="flaticon2-delivery-package text-primary"></i>
			</span>
			<h3 class="card-label">Stock List</h3> --}}
		</div>
	</div>
	<div class="card-body">
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
		<!--begin: Datatable-->
		<table class="table table-striped table-hover table-checkable" id="kt_datatable">
			<thead>
				<tr>
					<th>Batch ID</th>
					<th>Product</th>
					<th>Stock</th>
					<th>Discount</th>
					<th>MRP</th>
					<th>Retail Price</th>
					<th>Cost Price</th>
					<th>Manufacturing Date</th>
					<th>Expiry Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@if(count($allStock))
				@foreach($allStock as $app)
				<tr>
					<td>{{$app->batchId}}</td>
					<td>{{$app->productId}}</td>
					<td>{{$app->stock}}</td>
					<td>{{$app->discount}}%</td>
					<td>{{$app->mrpPrice}}</td>
					<td>{{$app->retailPrice}}</td>
					<td>{{$app->costPrice}}</td>
					<td>{{$app->manufDate}}</td>
					<td>{{$app->expiryDate}}</td>
					<td>
						<div class="btn-group">
							@if($app->status==1)
							<?php $us="Active"; $class="success"; ?>
							@else
							<?php $us="Inactive"; $class="danger"; ?>
							@endif

							<button type="button" class="btn btn-{{ $class }} dropdown-toggle"
								id="btn{{encryptor('encrypt',$app->id) }}" data-toggle="dropdown" aria-haspopup="true"
								aria-expanded="false">
								{{ $us }}
							</button>
							<div class="dropdown-menu">
								@if($app->status!=1)
								<a id="op{{encryptor('encrypt',$app->id) }}1"
									class="dropdown-item <?= $app->status==1?"active":"" ?> op{{encryptor('encrypt',$app->id) }}"
									href="#"
									onclick="changeStatus('{{encryptor('encrypt',$app->id) }}',1,'Active')">Active</a>
								@endif
								@if($app->status!=2)
								<a id="op{{encryptor('encrypt',$app->id) }}2"
									class="dropdown-item <?= $app->status==2?"active":"" ?> op{{encryptor('encrypt',$app->id) }}"
									href="#"
									onclick="changeStatus('{{encryptor('encrypt',$app->id) }}',2,'Inactive')">Inactive</a>
								@endif
							</div>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>

		</table>
		<div class="d-flex align-items-center justify-content-between">
			{{$allStock->links()}}
		</div>
		<!--end: Datatable-->
	</div>
</div>
<!--end::Card-->

@endsection

@push('scripts')
<script>
	function changeStatus(bid,status,ans) {
	
	$.ajax({
		'url': "@if(currentUser() === 'owner' || currentUser() === 'salesmanager'){{route(currentUser().'.changeStatusM')}}@endif",
		'type': 'GET',
		'data': {bid:bid,status:status},
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