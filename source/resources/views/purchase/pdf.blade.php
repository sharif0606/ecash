<html>
	<head>
		<!-- begin::Card-->
	</head>
	<body>
	<!-- begin::Card-->
	<div class="card card-custom overflow-hidden" id="printArea">
		<div class="card-body p-0">
			<!-- begin: Invoice-->
			<!-- begin: Invoice header-->
			<div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
				<div class="col-md-9">
					<div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
						<h1 class="display-4 font-weight-boldest mb-10">INVOICE</h1>
						<div class="d-flex flex-column align-items-md-end px-0">
							<!--begin::Logo-->
							<a href="#" class="mb-5">
								{{--<img src="{{asset("storage/images/company/$company->company_logo")}}" alt="" height="26" />--}}
								<span style="font-weight:bold; font-size:20px">{{ $company->company_name }}</span>
							</a>
							<!--end::Logo-->
							<span class="d-flex flex-column align-items-md-end opacity-70">
								<span> {{ $company->company_add_a }}</span>
								<span>{{ $company->company_add_b }}</span>
								<span>{{ $company->contact_number }}</span>
								<span>@if($company->company_email)  <abbr title="Email">E:</abbr> {{ $company->company_email }}  @endif
								    @if($company->webiste) <br><abbr title="Website">W:</abbr> {{ $company->webiste }} @endif</span>
							</span>
						</div>
					</div>
					
					<div class="border-bottom w-100"></div>
					<div class="d-flex justify-content-between pt-6">
						<div class="d-flex flex-column flex-root">
							<span class="font-weight-bolder mb-2">DATE</span>
							<span class="opacity-70">{{ date('M d,Y',strtotime($Purchase->purchase_date)) }}</span>
						</div>
						<div class="d-flex flex-column flex-root">
							<span class="font-weight-bolder mb-2">INVOICE NO.</span>
							<span class="opacity-70">{{ str_pad($Purchase->purchase_no,7,'0',STR_PAD_LEFT) }}</span>
						</div>
						<div class="d-flex flex-column flex-root">
							<span class="font-weight-bolder mb-2">INVOICE From.</span>
							<span class="opacity-70">
							    {{ $Purchase->supplier->name }},<br />{{ $Purchase->supplier->address }}
							    @if($Purchase->supplier->contact_no_a)
							        <br>
    								<abbr title="Phone">P:</abbr> {{ $Purchase->supplier->contact_no_a }}
    							@endif
								@if($Purchase->supplier->contact_no_b)
									, <br><span class="ml-3">{{ $Purchase->supplier->contact_no_b }}</span>
								@endif
								@if($Purchase->supplier->email)
								<br><abbr title="Email">E:</abbr> {{ $Purchase->supplier->email }}
								@endif
								@if($Purchase->supplier->website)
									<br><span class="ml-3">{{ $Purchase->supplier->website }}</span>
								@endif
							</span>
						</div>
					</div>
				</div>
			</div>
			<!-- end: Invoice header-->
			<!-- begin: Invoice body-->
			<div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
				<div class="col-md-9">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th class="pl-0 font-weight-bold text-muted text-uppercase">Description</th>
									<th class="text-right font-weight-bold text-muted text-uppercase">Quantity</th>
									<th class="text-right font-weight-bold text-muted text-uppercase">Rate</th>
									<th class="text-right font-weight-bold text-muted text-uppercase">Discount</th>
									<th class="text-right font-weight-bold text-muted text-uppercase">Tax</th>
									<th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Amount</th>
								</tr>
							</thead>
							<tbody>
								@if($items)
								@foreach($items as $item)
								<tr class="font-weight-boldest">
									<th class="pl-0 pt-7">{{ $item->product->name }}</th>
									<th class="text-right pt-7">{{ round($item->qty) }}</th>
									<td class="text-right pt-7">{{ $company->currency_symble }} {{ $item->price }}</td>
									<td class="text-right pt-7">{{ $company->currency }} {{ ($item->price*($item->discount/100)) }}</td>
									<td class="text-right pt-7">{{ $company->currency }} {{ ($item->price*($item->tax/100)) }}</td>
									<td class="text-right pt-7">{{ $company->currency_symble }} {{ $item->price*$item->qty }}</td>
								</tr>
								@endforeach
								@endif
							</tbody>
							<tfoot>
							    <tr>                                                        
									<td colspan="4" class="border-0"></td>
									<td class="text-right border-1 font-14"><b>Sub Total</b></td>
									<td class="text-right border-1 font-14"><b>{{ $company->currency_symble }} {{ number_format($Purchase->sub_total)  }}</b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0"></th>
									<td class="text-right border-1 font-14"><b>Tax</b></td>
									<td class="text-right border-1 font-14"><b> {{ $company->currency_symble }} {{ number_format($Purchase->total_tax) }} </b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0"></th>
									<td class="text-right border-1 font-14"><b>Discount</b></td>
									<td class="text-right border-1 font-14"><b> {{ $company->currency_symble }} {{ number_format($Purchase->total_dis) }} </b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0"></th>
									<td class="text-right border-1 font-14"><b>Due</b></td>
									<td class="text-right border-1 font-14"><b>{{ $company->currency_symble }} {{ number_format($Purchase->total_due) }} </b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0"></th>                                                        
									<td class="text-right border-1 font-14"><b>Total</b></td>
									<td class="text-right border-1 font-14"><b>{{ $company->currency_symble }} {{ number_format($Purchase->total_amount) }}</b></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			<!-- end: Invoice body-->
			<!-- begin: Invoice footer-->
			<div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0">
				<div class="col-md-9">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th class="font-weight-bold text-muted text-uppercase">BANK</th>
									<th class="font-weight-bold text-muted text-uppercase">ACC.NO.</th>
									<th class="font-weight-bold text-muted text-uppercase">DUE DATE</th>
									<th class="font-weight-bold text-muted text-uppercase">TOTAL AMOUNT</th>
								</tr>
							</thead>
							<tbody>
								<tr class="font-weight-bolder">
									<td>{{ $Purchase->bank_name }}</td>
									<td>{{ $Purchase->cheque_no }}</td>
									<td>@if($Purchase->cheque_date) {{ date('M d,Y',strtotime($Purchase->cheque_date)) }} @endif</td>
									<td class="text-danger font-size-h3 font-weight-boldest">{{ $company->currency_symble }} {{ $Purchase->total_amount }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<!-- end: Invoice footer-->
			<!-- end: Invoice-->
		</div>
	</div>
	<!-- end::Card-->
	</body>
	</html>