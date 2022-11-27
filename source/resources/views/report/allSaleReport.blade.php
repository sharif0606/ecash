@extends('layout.admin.admin_master')
@section('title', 'Sale Report List')
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
			<h3 class="card-label">Sale Report List</h3> --}}
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Reports</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Sale Report</a>
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
		<form method="GET" action="{{route(currentUser().'.allSaleReport')}}" class="kt-form kt-form--fit mb-15">
			<div class="row mt-8">
				<div class="col-lg-6 mb-lg-0">
					<label>Select Ranges</label>
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
				<div class="col-md-6">
					<div class="form-group">
						@php
						$term=array("All","Cash", "Due", "Card", "Bkash", "Roket");
						@endphp
						<label>Bill Terms <small class="text-danger font-13">*</small></label>
						<select name="bill_term" class="form-control saleType">
							@foreach($term as $k=>$v)
							<option value="{{$k}}" @if(isset($_GET['bill_term']) && $k==$_GET['bill_term']) selected
								@endif>{{$v}}</option>
							@endforeach
						</select>
					</div>
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
					<a href="{{route(currentUser().'.allSaleReport')}}" class="btn btn-secondary btn-secondary--icon"
						id="kt_reset">
						<span>
							<i class="la la-close"></i>
							<span>Reset</span>
						</span>
					</a></div>
			</div>
		</form>
		<!--begin: Datatable-->
		<table class="table table-bordered table-hover table-checkable" id="kt_datatable">
			<thead class="thead-light">
				<tr>
					<th>Sr</th>
					<th>Bill No</th>
					<th>Bill Date</th>
					<th>Bill Term</th>
					<th>Total Due</th>
					<th>Total Amount</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Sr</th>
					<th>Bill No</th>
					<th>Bill Date</th>
					<th>Bill Term</th>
					<th>Total Due</th>
					<th>Total Amount</th>
				</tr>
			</tfoot>
			<tbody>
				@if(count($allSaleReport))
				@foreach($allSaleReport as $index => $saleReport)
				<tr>
					<td>{{$index + 1}}</td>
					<td>{{$saleReport->bill_no}}</td>
					<td>{{$saleReport->bill_date}}</td>
					<td>
						<span class="badge badge-soft-success">{{$term[$saleReport->bill_term]}}</span>
					</td>
					<td>{{$saleReport->total_due}}</td>
					<td>{{$saleReport->total_amount}}</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		<div class="d-flex align-items-center justify-content-between">
			<!--{{$allSaleReport->links()}}-->
			@include('pagination.default', ['paginator' => $allSaleReport])
		</div>
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
        var start = moment().subtract(1, 'month').startOf('month');
        var end = moment().subtract(1, 'month').endOf('month');
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