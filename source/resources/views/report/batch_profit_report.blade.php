@extends('layout.admin.admin_master')
@section('title', 'Batch Wise Profit Report')
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
<div class="card card-custom">
	<div class="card-header">
		<div class="card-title">
			{{-- <span class="card-icon">
				<i class="flaticon2-delivery-package text-primary"></i>
			</span>
			<h3 class="card-label">Batch Wise Profit Report</h3> --}}
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Reports</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Batch Wise Profit Report</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">List</a>
				</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
	</div>
	<div class="card-body">
		<!--begin: Search Form-->
		<form method="GET" action="{{route(currentUser().'.allbatchWiseProfit')}}" class="kt-form kt-form--fit mb-15">
			<div class="row mt-8">
				<div class="col-lg-6 mb-lg-0 mb-6">
					<label>Sell Date:</label>
					<div class='input-group'>
						<input type="text" class="form-control" readonly="readonly" placeholder="Select date range"
							name="dateQuery" id="dateQuery" />
						<div class="input-group-append">
							<span class="input-group-text">
								<i class="la la-calendar-check-o"></i>
							</span>
						</div>
					</div>
				</div>

				<div class="col-lg-6 mb-lg-0 mb-6">
					<label>Product:</label>
					<select class="form-control datatable-input" name="product" data-col-index="2">
						<option value="">Select Product</option>
						@if(count($allProduct) > 0)
						@foreach($allProduct as $product)
						<option value="{{ $product->id }}" @if(isset($_GET['product']) && $product->
							id==$_GET['product']) selected @endif >{{ $product->brandName }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="row mt-8">
				<div class="col-lg-12">
					<button class="btn btn-primary btn-primary--icon" id="kt_search">
						<span>
							<i class="la la-search"></i>
							<span>Search</span>
						</span>
					</button>&#160;&#160;
					<a href="{{route(currentUser().'.allbatchWiseProfit')}}"
						class="btn btn-secondary btn-secondary--icon" id="kt_reset">
						<span>
							<i class="la la-close"></i>
							<span>Reset</span>
						</span>
					</a>
				</div>
			</div>
		</form>
		<!--begin: Datatable-->
		<table class="table table-bordered table-hover table-checkable" id="kt_datatable">
			<thead class="thead-light">
				<tr>
					<th>Sr</th>
					<th>BatchId</th>
					<th>Medicine</th>
					<th>Cost</th>
					<th>Sell</th>
					<th>Profit</th>
				</tr>
			</thead>
			<tbody>
				@php $pro=0; @endphp
				@if(count($profit))
				@foreach($profit as $index => $batchReport)
				@php $pro+=(round($batchReport->sell,2) - round($batchReport->cost,2)); @endphp
				<tr>
					<td>{{$index + 1}}</td>
					<td>{{ $batchReport->batchId}}</td>
					<td>{{ $batchReport->medicine}}</td>
					<td>{{ round($batchReport->cost,2)}}</td>
					<td>{{ round($batchReport->sell,2)}}</td>
					<td>{{ (round($batchReport->sell,2) - round($batchReport->cost,2)) }}</td>
				</tr>
				@endforeach
				@endif
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th>Total</th>
					<th>{{$pro}}</th>
				</tr>
			</tfoot>
		</table>

		<!--end: Datatable-->
	</div>
</div>
<!--end::Card-->
@endsection

@push('scripts')
<script>
	// predefined ranges
	@if(isset($_GET['dateQuery']))
		var start = moment("{{ explode('-',$_GET['dateQuery'])[0]}}",'DD/MM/YYYY');
        var end = moment("{{ explode('-',$_GET['dateQuery'])[1]}}",'DD/MM/YYYY');
	@else
        var start = moment();
        var end = moment().add(29, 'days');
	@endif
	
        $('#dateQuery').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
			locale: {
			  format: 'DD/MM/YYYY'
			},

            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'Next 30 Days': [moment(), moment().add(29, 'days')],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
			$('#dateQuery').val( start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        });
 
</script>

@endpush