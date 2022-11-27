<!DOCTYPE html>
<html>

<head>
	<title>Invoice</title>
	<style>
		@import url('https://fonts.maateen.me/kalpurush/font.css');

		* {
			padding: 0;
			margin: 0;
			font-family: sans-serif;
		}

		.font-bold {
			font-weight: bold;
		}

		.table {
			border-bottom: 1px solid #ddd;
			width: 100%;
			margin-bottom: 1rem;
			color: #212529;
			border-collapse: collapse;

		}

		.table th {
			vertical-align: bottom;
			border-bottom: 2px solid #dee2e6;
			text-align: left;
		}

		.table td,
		.table th {
			padding: .75rem;
			vertical-align: top;
			border-top: 1px solid #dee2e6;
		}

		.table tbody tr td:nth-child(4) {
			text-align: right;
		}

		.container {
			width: 1140px;
			display: block;
			margin: auto;
		}

		.row {
			width: 100%;
		}

		.col-12 {
			width: 100%;
		}

		.text-left {
			text-align: left;
		}

		.display-4 {
			font-size: 3.5rem;
		}

		.text-uppercase {
			text-transform: uppercase;
		}

		.folat-none {
			float: none;
		}

		.float-right {
			float: right;
		}

		.clearfix {
			clear: both;
		}

		.text-capitalize {
			text-transform: capitalize;
		}

		.d-none {
			display: none;
		}

		h3 {
			font-size: 1.75rem;
			font-weight: 500;
			line-height: 1.2;
		}

		p {
			margin-top: 0;
			margin-bottom: 1rem !important;
			font-size: 18px;
			letter-spacing: 1px;
		}

		.d-inline-block {
			display: inline-block;
		}

		.mr-4 {
			margin-right: 1.5rem;
		}

		h4 {
			font-size: 1.5rem;
		}

		h2 {
			margin-bottom: .5rem;
			font-weight: 500;
			line-height: 1.2;
			font-size: 2rem;
		}

		.display-4 {
			font-size: 3.5rem;
			font-weight: 300;
			line-height: 1.2;
		}

		h6 {
			font-size: 1rem;
			margin-bottom: .5rem;
			font-weight: 500;
			line-height: 1.2;
		}

		a:hover {
			color: #0056b3;
		}

		a {
			display: block;
			color: #007bff;
		}

		.m-0 {
			margin: 0 !important;
		}

		.p-0 {
			padding: 0 !important;
		}

		.footer {
			padding: 50px 10px;
			background: #ddd;
		}
	</style>
</head>

<body>
	<div class="main-bg container">
		<div class="row">
			<div style="width:70%; float:left">
				<div class="header">
					<div class="text-left">
						<h1 style="font-family: bangla;" class="display-4 text-uppercase font-bold">Invoice</h1>
					</div>
				</div>
				<div style="width:48%; float:left" class="float-md-left folat-none mr-md-4 pr-md-4">
					<p class="text-uppercase">
						invoice number <br>
						{{ $company->shopCode }}-{{date('y')}}-{{ str_pad($bill->bill_no,8,'0',STR_PAD_LEFT) }}
					</p>
				</div>
				<div style="width:48%; float:right" class="ml-md-4 pl-md-4">
					<p class="text-uppercase">
						date of issue <br>
						{{ date('d/m/Y',strtotime($bill->bill_date)) }}
					</p>
				</div>
			</div>
			<div style="width:30%; float:left">

				<div class="float-right">
					<!--      logo input here and cut h1         <img src="" alt="">-->
					<h2></h2>
				</div>
			</div>
			<div class="clearfix"></div>

		</div>
		<div class="row">
			<div style="width:50%; float:left">
				<h3 class="m-0 p-0 text-uppercase clr">billed to</h3>
				<p class="m-0 p-0 text-capitalize">{{ $bill->customer->name }}</p>
				<p class="m-0 p-0 text-capitalize">{{ $bill->customer->address }}</p>
				<p class="m-0 p-0 text-capitalize">
					@if($bill->customer->custCode)
					{{ $bill->customer->custCode }}
					@endif
					@if($bill->customer->contact_no_b)
					, {{ $bill->customer->contact_no_b }}
					@endif
				</p>
				<p class="m-0 p-0 text-capitalize">
					@if($bill->customer->email)
					{{ $bill->customer->email }}
					@endif
				</p>
			</div>
			<div style="width:50%; float:right">
				<h1 class="text-capitalize" style="margin:0;">{{ $company->company_name }}</h1>
				<p class="m-0 p-0 text-capitalize">{{ $company->company_add_a }}</p>
				<p class="m-0 p-0 text-capitalize">{{ $company->company_add_b }}</p>
				<p class="m-0 p-0 text-capitalize">{{ $company->contact_number }}</p>
				<p class="m-0 p-0 ">
					@if($company->company_email)
					{{ $company->company_email }}
					@endif
				</p>
				<p class="m-0 p-0 ">
					@if($company->webiste)
					{{ $company->webiste }}
					@endif
				</p>
			</div>
		</div>
		<br><br>
		<div>
			<table class="table">
				<thead>
					<tr>
						<th class="text-uppercase">description</th>
						<th class="text-uppercase">unit cost</th>
						<th class="text-uppercase">QT</th>
						<th class="text-uppercase">amount</th>
					</tr>
				</thead>
				<tbody>
					@if($items)
					@foreach($items as $item)
					<tr>
						<td>{{ $item->product->brandName }}
						        @if($item->product->warrenty)
						           (warrenty: {{$item->product->warrenty}} )
						        @endif
						</td>
						<td>{{ $item->price }} {{ $company->currency }}</td>
						<td>{{ round($item->qty) }}</td>
						<td>{{ $company->currency }} {{ $item->price*$item->qty }}  </td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
		<div class="row">
			<div style="width:50%; float:left">
				<h2 style="text-transform: uppercase;margin: 30px 0 0 0;font-size: 2rem;" class="total text-uppercase">
					total invoice</h2>
				<h3 style="font-size: 40px;color: #08d7d7;margin: 0px;">{{ $company->currency }} {{ $bill->total_amount }}</h3>
			</div>
			<div style="width:50%;float:right;text-align:right" class="text-md-right pr-4 mb-2">
				<h4
					style="width:60%;float:left;margin:0;display: inline-block;text-transform: uppercase;font-weight: bold;">
					sub total</h4><span>{{ $company->currency }} {{ $bill->sub_total  }}</span>
				<div class="clearfix"></div>
				<h4
					style="width:60%;float:left;margin:0;display: inline-block;text-transform: uppercase;font-weight: bold;">
					discount</h4><span>{{ $company->currency }} {{ $bill->total_dis }}</span>
				<div class="clearfix"></div>
				<h4
					style="width:60%;float:left;margin:0;display: inline-block;text-transform: uppercase;font-weight: bold;">
					tax</h4><span>{{ $company->currency }} {{ $bill->total_tax }}</span>
				<div class="clearfix"></div>
				<h4
					style="width:60%;float:left;margin:0;display: inline-block;text-transform: uppercase;font-weight: bold;">
					total</h4><span>{{ $company->currency }} {{ $bill->total_amount }}</span>
				<div class="clearfix"></div>
				<h4
					style="width:60%;float:left;margin:0;display: inline-block;text-transform: uppercase;font-weight: bold;">
					Due</h4><span>{{ $company->currency }} {{ $bill->total_due }}</span>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="text-left" style="padding-left: 15px">
			<h6 class="text-uppercase">terms</h6>
			<pre>{{ $company->billing_terms }}</pre>
		</div>
		<div class="footer">
			<footer class=" float-md-right">
				<a href="#" style="text-decoration: underline">powered by Cashbaksho</a>
			</footer>
		</div>
	</div>
</body>

</html>