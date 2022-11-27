@extends('layout.admin.admin_master')
@section('title', 'Sale Report List')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b"
				style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
				<!--begin::Body-->
				<div class="card-body">
					<a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">Today
						Sales</a>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Amount
						<h3 style="margin:10px 0;">৳ {{$todaySellSummary[0]->todayTotalSellAmount}}</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Product Purchase Amount
						<h3 style="margin:10px 0;">৳ {{$todaySellSummary[0]->todayTotalProductAmount}}</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Profit
						<h3 style="margin:10px 0; color:#4CAF50">
							৳
							{{$todaySellSummary[0]->todayTotalSellAmount - $todaySellSummary[0]->todayTotalProductAmount}}
						</h3>
					</div>
				</div>
				<!--end::Body-->
			</div>
		</div>

		<div class="col-md-4">
			<div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b"
				style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
				<!--begin::Body-->
				<div class="card-body">
					<a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">Yesterday
						Sales</a>

					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Amount
						<h3 style="margin:10px 0;">৳ @if($yesterdaySellSummary[0]->yesterdayTotalSellAmount > 0)
							{{$yesterdaySellSummary[0]->yesterdayTotalSellAmount}}
							@else
							{{'0'}}
							@endif</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Product Purchase Amount
						<h3 style="margin:10px 0;">৳ @if($yesterdaySellSummary[0]->yesterdayTotalProductAmount > 0)
							{{$yesterdaySellSummary[0]->yesterdayTotalProductAmount}}
							@else
							{{'0'}}
							@endif
						</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Profit
						<h3 style="margin:10px 0; color:#4CAF50">
							৳
							{{$yesterdaySellSummary[0]->yesterdayTotalSellAmount - $yesterdaySellSummary[0]->yesterdayTotalProductAmount}}
						</h3>
					</div>
				</div>
				<!--end::Body-->
			</div>
		</div>

		<div class="col-md-4">
			<div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b"
				style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
				<!--begin::Body-->
				<div class="card-body">
					<a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">The Weekly
						Sales</a>

					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Amount
						<h3 style="margin:10px 0;">৳ {{$weeklySellSummary[0]->weeklyTotalSellAmount}}</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Product Purchase Amount
						<h3 style="margin:10px 0;">৳ {{$weeklySellSummary[0]->weeklyTotalProductAmount}}</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Profit
						<h3 style="margin:10px 0; color:#4CAF50">
							৳
							{{$weeklySellSummary[0]->weeklyTotalSellAmount - $weeklySellSummary[0]->weeklyTotalProductAmount}}
						</h3>
					</div>
				</div>
				<!--end::Body-->
			</div>
		</div>

		<div class="col-md-4">
			<div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b"
				style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
				<!--begin::Body-->
				<div class="card-body">
					<a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">The Monthly
						Sales</a>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Amount
						<h3 style="margin:10px 0;">৳ {{$monthlySellSummary[0]->monthlyTotalSellAmount}}</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Product Purchase Amount
						<h3 style="margin:10px 0;">৳ {{$monthlySellSummary[0]->monthlyTotalProductAmount}}</h3>
					</div>
					<div class="text-dark-50 font-weight-bold font-size-lg pt-2">
						Total Sell Profit
						<h3 style="margin:10px 0; color:#4CAF50">
							৳
							{{$monthlySellSummary[0]->monthlyTotalSellAmount - $monthlySellSummary[0]->monthlyTotalProductAmount}}
						</h3>
					</div>
				</div>
				<!--end::Body-->
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')

@endpush