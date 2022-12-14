@extends('layout.admin.admin_master')

@section('title', 'Invoice')

@section('content')

	<!-- begin::Card-->
	<div class="card card-custom overflow-hidden" id="printArea">
		<div class="card-body p-2">
			<!-- begin: Invoice-->
			<!-- begin: Invoice header-->
			<div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
				<div class="col-md-11">
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
							<span class="fw-bold">DATE</span>
							<span class="opacity-70">{{ date('M d,Y',strtotime($Purchase->purchase_date)) }}</span>
						</div>
						<div class="d-flex flex-column flex-root">
							<span class="fw-bold">INVOICE NO.</span>
							<span class="opacity-70">{{ str_pad($Purchase->purchase_no,7,'0',STR_PAD_LEFT) }}</span>
						</div>
						<div class="d-flex flex-column flex-root">
							<span class="fw-bold">INVOICE From.</span>
							<span class="opacity-70">
							    {{ $Purchase->supplier->name }},
								@if($Purchase->supplier->address)<br />{{ $Purchase->supplier->address }} @endif
							    @if($Purchase->supplier->supCode)
							        <br>
    								<abbr title="Phone">P:</abbr> {{ $Purchase->supplier->supCode }}
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
				<div class="col-md-11">
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
									<td colspan="4" class="border-0 bg-white"></td>
									<td class="text-right border-1 font-14"><b>Sub Total</b></td>
									<td class="text-right border-1 font-14"><b>{{ $company->currency_symble }} {{ number_format($Purchase->sub_total)  }}</b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0 bg-white"></th>
									<td class="text-right border-1 font-14"><b>Tax</b></td>
									<td class="text-right border-1 font-14"><b> {{ $company->currency_symble }} {{ number_format($Purchase->total_tax) }} </b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0 bg-white"></th>
									<td class="text-right border-1 font-14"><b>Discount</b></td>
									<td class="text-right border-1 font-14"><b> {{ $company->currency_symble }} {{ number_format($Purchase->total_dis) }} </b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0 bg-white"></th>
									<td class="text-right border-1 font-14"><b>Due</b></td>
									<td class="text-right border-1 font-14"><b>{{ $company->currency_symble }} {{ number_format($Purchase->total_due) }} </b></td>
								</tr>
								<tr>
									<th colspan="4" class="border-0 bg-white"></th>                                                        
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
				<div class="col-md-11">
					<div class="table-responsive">
					    @if($Purchase->purchase_term==2 || $Purchase->purchase_term==3)
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
						@endif
						@if($Purchase->purchase_term==4)
						
						<table class="table">
							<thead>
								<tr>
									<th class="font-weight-bold text-muted text-uppercase">Mobile BANK</th>
									<th class="font-weight-bold text-muted text-uppercase">DUE DATE</th>
									<th class="font-weight-bold text-muted text-uppercase">TOTAL AMOUNT</th>
								</tr>
							</thead>
							<tbody>
								<tr class="font-weight-bolder">
									<td>
									    <b>Bank:</b>{{ mobile_bank()[$Purchase->mbank_name] }}<br>
									    <b>Sender:</b>{{ $Purchase->sender_no }}<br>
									    <b>Supplier:</b>{{ $Purchase->receiver_no }}<br>
									    <b>Note:</b>{{ $Purchase->m_note }}<br>
									
									</td>
									<td>@if($Purchase->cheque_date) {{ date('M d,Y',strtotime($Purchase->cheque_date)) }} @endif</td>
									<td class="text-danger font-size-h3 font-weight-boldest">{{ $company->currency_symble }} {{ $Purchase->total_amount }}</td>
								</tr>
							</tbody>
						</table>
						@endif
					</div>
				</div>
			</div>
			
			<!-- end: Invoice footer-->
			<!-- begin: Invoice action-->
			<div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
				<div class="col-md-9">
					<div class="d-flex justify-content-between">
						<a class="btn btn-info font-weight-bold dontPrint" href="{{route(currentUser().'.purchasePDF',[encryptor('encrypt', $Purchase->id),Replace('download')])}}">Download Invoice</a>
						<a class="btn btn-primary font-weight-bold dontPrint" href="{{route(currentUser().'.purchasePDF',[encryptor('encrypt', $Purchase->id),Replace('print')])}}" target="_blank">Print Invoice</a>
					</div>
				</div>
			</div>
			<!-- end: Invoice action-->
			<!-- end: Invoice-->
		</div>
	</div>
	<!-- end::Card-->
@endsection