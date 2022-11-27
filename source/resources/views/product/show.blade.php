@extends('layout.admin.admin_master')
@section('title', 'Show Product')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Product Show</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Product</a></li>
				<li class="breadcrumb-item active">Show</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="content-header-right col-md-3 col-6 mb-2">
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
							Product Details
						</h4>
					</div>
					<div class="card-body">
	                    <div class="row">
                    	    <div class="col-sm-6">
                    	        <div class="d-flex flex-column">
                    	            <span class="fw-bolder mb-25">
                    	                <img src="{{asset('/')}}storage/images/product/thumbnail/{{$data->thumbnail}}" alt="" class="img-thumbnail thumb-sm mr-1" width="100px">
                    	            </span>
                    	        </div>
                    	    </div>
                    	    <div class="col-sm-6">
                    	        <div class="row">
                    	            <div class="col-sm-12">
                    	                <b>Product Name:</b> {{ $data->name }}
                    	            </div>
                    	            <div class="col-sm-12">
                    	                <b>Brand:</b> @if($data->brand){{$data->brand->name}}@endif
                    	            </div>
                    	            <div class="col-sm-12">
                    	                <b>Product Type / Category:</b> @if($data->categories){{$data->categories->name}}@endif
                    	            </div>
                    	            <div class="col-sm-12">
                    	                <b>Model No:</b> {{$data->modelNo}}
                    	            </div>
                    	            <div class="col-sm-12">
                    	                <b>Model Name:</b> {{$data->modelName}}
                    	            </div>
                    	            <div class="col-sm-12">
                    	                <b>Warrenty:</b> {{$data->warrenty}}
                    	            </div>
                    	            <div class="col-sm-12">
                    	                <b>Status:</b> @if($data->status) Active @else Inactive @endif
                    	            </div>
                    	        </div>
                    	    </div>
                    	</div>
                    	<hr>
                    	<div class="row">
                    	    <div class="col-sm-6">
                    	        <b>Short Description:</b>  {{ $data->shortDescription}}
                    	    </div>
                    	    <div class="col-sm-6">
                    	        <b>Full Description:</b>  {{ $data->description}}
                    	    </div>
                    	</div>
                    	<hr>
                    	<div class="row">
                    	    <div class="col-sm-12">
                    	        <table class="table">
                    	            <tr>
                    	                <th>Batch</th>
                    	                <th>Stock</th>
                    	                <th>Sell Price</th>
                    	                <th>Buy Price</th>
                    	                <th>Discount</th>
                    	                <th>Tax</th>
                    	                <th>Expiry Date</th>
                    	                <th>Details</th>
                    	            </tr>
                    	            @if($data->stocks)
                    	                @foreach($data->stocks as $s)
                    	                    <tr>
                    	                        <td>{{$s->batchId}}</td>
                    	                        <td>{{$s->stock}}</td>
                    	                        <td>{{$s->sellPrice}}</td>
                    	                        <td>{{$s->buyPrice}}</td>
                    	                        <td>{{$s->discount}}</td>
                    	                        <td>{{$s->tax}}</td>
                    	                        <td>{{$s->expiryDate?date('d-m-Y',strtotime($s->expiryDate)):""}}</td>
                    	                        <td>
                    	                            <b>Serial No: </b>{{$s->serialNo}}<br>
                    	                            <b>Ram: </b>{{$s->ram}}<br>
                    	                            <b>Storage: </b>{{$s->storage}}<br>
                    	                            <b>Color: </b>{{$s->color}}<br>
                    	                            <b>IMEI 1: </b>{{$s->imei_1}}<br>
                    	                            <b>IMEI 2: </b>{{$s->imei_2}}
                    	                        </td>
                    	                    </tr>
                    	                @endforeach
                    	            @endif
                    	        </table>
                    	    </div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->
@endsection

@push('scripts')
<script>
	var avatar1 = new KTImageInput('kt_image_1');
	$('.select2').select2();
</script>
@endpush