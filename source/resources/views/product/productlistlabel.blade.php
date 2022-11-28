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
								<li class="breadcrumb-item"><a href="#">Product</a></li>
								<li class="breadcrumb-item active">Label</li>
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
								All Product List Here to Generate Label...
							</h4>
						</div>
						<div class="card-body">
							<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
								<div class="row">
									<div class="col-12 col-sm-8">
										<!--begin: Search Form-->
										<form method="GET" action="{{route(currentUser().'.productlistlabel')}}" class="kt-form kt-form--fit mb-15">
											<div class="row mb-2">
												<div class="col-lg-6 mb-lg-0 mb-6">
													<label>Name:</label>
													<input type="text" class="form-control" name="name" value="@if(Session::get('name')){{Session::get('name')}}@endif">
												</div>
												<div class="col-lg-6 mb-lg-0 mb-6 pt-2">
													<button class="btn btn-primary btn-primary--icon" id="kt_search">
														<span>
															<i class="la la-search"></i>
															<span>Search</span>
														</span>
													</button>&#160;&#160;
													<a href="{{route(currentUser().'.productlistlabel')}}?fresh=1" class="btn btn-secondary btn-secondary-icon" id="kt_reset">
														<span>
															<i class="la la-close"></i>
															<span>Reset</span>
														</span>
													</a>
												</div>
											</div>
										</form>
											
										<div class="table-responsive">
                                    		<!--begin: Datatable-->
											<a href="javascript:void(0)" onclick="barcode_preview()" class="mr-2 btn btn-outline-info"><i class="fa fa-barcode"></i> Barcode</a>
                                    		<a href="javascript:void(0)" onclick="qrcode_preview()" class="mr-2 btn btn-outline-info"><i class="fa fa-qrcode"></i> Qrcode</a>
											<table class="my-1 table table-striped text-center table-bordered dt-responsive">
                                    			<thead class="thead-light">
                                    				<tr>
                                    					<th> <label for="checkall"> <input type="checkbox" name="" class="checkall" value="1"> </label></th>
                                    					<th>Name</th>
                                    					<th>Sell Price</th>
                                    				</tr>
                                    			</thead>
                                    			<tbody>
                                    				@if(count($productlist))
                                    					@foreach($productlist as $index => $product)
                                    					<tr id="{{$index + 1}}">
                                    						<td><label for="check"> <input type="checkbox" name="" class="get_data" value="{{$product->id}}"> </label></td>
                                    						<td>{{$product->name}} ({{$product->stock}})</td>
                                    						<td>{{$product->sellPrice}}</td>
                                    					</tr>
                                    					@endforeach
                                    				@endif
                                    			</tbody>
                                    		</table><!--end: Datatable-->
											<div class="d-flex align-items-center justify-content-between">
											{{$productlist->links()}}	
											</div>
										</div>
                                	</div>
									<div class="col-12 col-sm-4 barcodedata" style="max-height:1200px; overflow:auto">

									</div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- Responsive tables end -->
	</div>
</div><!--end::Card-->
@endsection

@push('scripts')
<script>
	function barcode_preview(){
		var get_data=new Array();
		$('.get_data').each(function(){
			if($(this).is(":checked"))
				get_data.push($(this).val());
		});

		var checkall=$('.checkall').is(":checked")?1:0;
		$.ajax({
			'url': '{{route(currentUser().'.barcodeprintpreview')}}',
			'type': 'GET',
			'dataType' : 'json',
			'data': {datas:get_data,checkall:checkall},
			success: function(response){ // What to do if we succeed
			
				console.log(response);
				$('.barcodedata').html(response);
			},
			error: function(response){
				console.log(response);
			}
		});
	}
	function qrcode_preview(){
		var get_data=new Array();
		$('.get_data').each(function(){
			if($(this).is(":checked"))
				get_data.push($(this).val());
		});

		var checkall=$('.checkall').is(":checked")?1:0;
		$.ajax({
			'url': '{{route(currentUser().'.qrcodeprintpreview')}}',
			'type': 'GET',
			'dataType' : 'json',
			'data': {datas:get_data,checkall:checkall},
			success: function(response){ // What to do if we succeed
			
				console.log(response);
				$('.barcodedata').html(response);
			},
			error: function(response){
				console.log(response);
			}
		});
	}

	function print_label(ptype,ltype){
		var get_data=new Array();
		$('.get_data').each(function(){
			if($(this).is(":checked"))
				get_data.push($(this).val());
		});

		var checkall=$('.checkall').is(":checked")?1:0;
		$.ajax({
			'url': '{{route(currentUser().'.labelprint')}}',
			'type': 'GET',
			'dataType' : 'json',
			'data': {datas:get_data,checkall:checkall,ptype:ptype,ltype:ltype},
			success: function(response){ // What to do if we succeed
				var divContents = response;
				var a = window.open('', '', 'height=700, width=800');
				a.document.write('<html>');
				a.document.write('<body >');
				a.document.write(divContents);
				a.document.write('</body></html>');
				a.document.close();
				a.onload=function(){
					a.print();
				};
			},
			error: function(response){
				console.log(response);
			}
		});
	}
</script>
@endpush