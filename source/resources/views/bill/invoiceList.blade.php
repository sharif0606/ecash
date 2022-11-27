@extends('layout.admin.admin_master')

@section('title', 'Bill List')
@section('content')
{{-- <style>
@font-face {
		font-family: 'kalp';
		src: url({{asset('/')}}assets/css/Kalpurush.ttf) format("truetype");
font-weight: 400; // use the matching font-weight here ( 100, 200, 300, 400, etc).
font-style: normal; // use the matching font-style here
}
body {
font-family: "kalp";
}
</style> --}}
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
	<div class="card-header flex-wrap border-0 pt-6 pb-0">
		<div class="card-title">
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="" class="text-muted">Sale</a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted">List</a>
				</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<div class="card-toolbar">
			<!--begin::Dropdown-->
			<div class="dropdown dropdown-inline mr-2">
				<button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
					data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="svg-icon svg-icon-md">
						<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
							height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<rect x="0" y="0" width="24" height="24" />
								<path
									d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
									fill="#000000" opacity="0.3" />
								<path
									d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
									fill="#000000" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>Export</button>
				<!--begin::Dropdown Menu-->
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
					<!--begin::Navigation-->
					<ul class="navi flex-column navi-hover py-2">
						<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Choose
							an option:</li>
						<li class="navi-item">
							<a href="#" class="navi-link">
								<span class="navi-icon">
									<i class="la la-print"></i>
								</span>
								<span class="navi-text">Print</span>
							</a>
						</li>
						<li class="navi-item">
							<a href="#" class="navi-link">
								<span class="navi-icon">
									<i class="la la-copy"></i>
								</span>
								<span class="navi-text">Copy</span>
							</a>
						</li>
						<li class="navi-item">
							<a href="#" class="navi-link">
								<span class="navi-icon">
									<i class="la la-file-excel-o"></i>
								</span>
								<span class="navi-text">Excel</span>
							</a>
						</li>
						<li class="navi-item">
							<a href="#" class="navi-link">
								<span class="navi-icon">
									<i class="la la-file-text-o"></i>
								</span>
								<span class="navi-text">CSV</span>
							</a>
						</li>
						<li class="navi-item">
							<a href="#" class="navi-link">
								<span class="navi-icon">
									<i class="la la-file-pdf-o"></i>
								</span>
								<span class="navi-text">PDF</span>
							</a>
						</li>
					</ul>
					<!--end::Navigation-->
				</div>
				<!--end::Dropdown Menu-->
			</div>
			<!--end::Dropdown-->
			<!--begin::Button-->
			<a href="{{route(currentUser().'.addNewBillForm')}}" class="btn btn-primary font-weight-bolder">
				<span class="svg-icon svg-icon-md">
					<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
						height="24px" viewBox="0 0 24 24" version="1.1">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<rect x="0" y="0" width="24" height="24" />
							<circle fill="#000000" cx="9" cy="15" r="6" />
							<path
								d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
								fill="#000000" opacity="0.3" />
						</g>
					</svg>
					<!--end::Svg Icon-->
				</span>New Bill</a>
			<!--end::Button-->
		</div>
	</div>
	<div class="card-body">
		<!--begin: Datatable-->
		<div class="d-flex">
			<ul class="w-100 d-flex align-center justify-content-between customer-summary mb-3 p-3">
				<li>
					<strong>Customer </strong><span class="d-block">{{$billSummary->name}}</span>
				</li>
				<li>
					<strong>Contact </strong><span class="d-block">{{$billSummary->custCode}}</span>
				</li>
				<li>
					<strong>Total Purchase </strong><span class="d-block">{{$billSummary->totalPurchase}}</span>
				</li>
				<li>
					<strong>Total Amount </strong><span class="d-block">{{$billSummary->totalAmount}}</span>
				</li>
				<li>
					<strong>Total Due </strong>
					@if ($billSummary->totalDue == 0.00)
					<span class="text-success d-block">Clear</span>
					@else
					<span class="text-danger d-block">{{$billSummary->totalDue}}</span>
					@endif
				</li>
			</ul>
			<button data-customerId="{{$billSummary->customerId}}" data-totalDue="{{$billSummary->totalDue}}"
				id="payBillBtn" type="button" style="width: 100px" class="btn btn-primary ml-3 mb-3">Pay Bill</button>
		</div>

		<div id="duePayForm" class="my-3 d-none">
			<ul class="d-flex align-center justify-content-between customer-summary mb-3 p-3">
				<div class="form-group w-100 m-0">
					<input autocomplete="off" value="{{$billSummary->totalDue}}" type="text" name="payAmount"
						class="form-control" placeholder="Input amount you want to pay">
				</div>
				<button type="button" id="payDue" class="btn btn-success ml-3">Pay</button>
				<button type="button" id="cancelPayDue" class="btn btn-danger ml-3">Cancel</button>
			</ul>
		</div>


		<div class="table-responsive">
			<?php $term=array('','Cash','Credit','Card','Bkash','Rocket'); ?>
			<table class="table table-striped mb-0">
				<thead class="thead-light">
					<tr>
						<th>Bill No</th>
						<th>Bill Date</th>
						<th>Customer</th>
						<th>Address</th>
						<th>Contact</th>
						<th>Email</th>
						<th>Term</th>
						<th>Tax/ Vat</th>
						<th>Dsicount</th>
						<th>Due</th>
						<th>Total</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@if(count($allBill))
					@foreach($allBill as $bill)
					<tr>
						<td>{{$bill->bill_no}}</td>
						<td>{{ date('M d, Y',strtotime($bill->bill_date))}}</td>
						<td>{{$bill->customer->name}}</td>
						<td>{{$bill->customer->address}}</td>
						<td>{{$bill->customer->custCode}}</td>
						<td>{{$bill->customer->email}}</td>
						<td>{{$term[$bill->bill_term]}} </td>
						<td>{{$bill->total_tax}}</td>
						<td>{{$bill->total_dis}}</td>
						<td>{{$bill->total_due}}</td>
						<td>{{$bill->total_amount}}</td>
						<td>
							@if(currentUser() === 'salesman')
							@if($bill->status==1)
							<a class="btn btn-success"> Approved </a>
							@elseif($bill->status==2)
							<a class="btn btn-danger"> Canceled </a>
							@elseif($bill->status==3)
							<a class="btn btn-info"> Partial Return </a>
							@elseif($bill->status==4)
							<a class="btn btn-warning"> Full Return </a>
							@else
							<a class="btn btn-danger"> Waste Return </a>
							@endif
							@else
							<div class="btn-group">
								@if($bill->status==1)
								<?php $us="Approved"; $class="success"; ?>
								@elseif($bill->status==2)
								<?php $us="Canceled"; $class="danger"; ?>
								@elseif($bill->status==3)
								<?php $us="Partial Return"; $class="info"; ?>
								@elseif($bill->status==4)
								<?php $us="Full Return"; $class="warning"; ?>
								@else
								<?php $us="Waste Return"; $class="danger"; ?>
								@endif

								<button type="button" class="btn btn-{{ $class }} dropdown-toggle"
									id="btn{{encryptor('encrypt',$bill->id) }}" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false">
									{{ $us }}
								</button>
								<div class="dropdown-menu">
									@if($bill->status!=1)
									<a id="op{{encryptor('encrypt',$bill->id) }}1"
										class="dropdown-item <?= $bill->status==1?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}"
										href="#"
										onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',1,'Approved')">Approved</a>
									@endif
									@if($bill->status!=2)
									<a id="op{{encryptor('encrypt',$bill->id) }}2"
										class="dropdown-item <?= $bill->status==2?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}"
										href="#"
										onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',2,'Canceled')">Canceled</a>
									@endif
									@if($bill->status!=3 && $bill->status!=2 && $bill->status!=4)
									{{--<a id="op{{encryptor('encrypt',$bill->id) }}3" class="dropdown-item
									<?= $bill->status==3?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}" href="#"
									onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',3,'Partial
									Return')">Partial Return</a>--}}
									@endif
									@if($bill->status!=4 && $bill->status!=2)
									<a id="op{{encryptor('encrypt',$bill->id) }}4"
										class="dropdown-item <?= $bill->status==4?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}"
										href="#"
										onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',4,'Full Return')">Full
										Return</a>
									@endif
									@if($bill->status!=5 && $bill->status!=2 && $bill->status!=4)
									<a id="op{{encryptor('encrypt',$bill->id) }}5"
										class="dropdown-item <?= $bill->status==5?"active":"" ?> op{{encryptor('encrypt',$bill->id) }}"
										href="#"
										onclick="changeStatus('{{encryptor('encrypt',$bill->id) }}',5,'Waste Return')">Waste
										Return</a>
								</div>
								@endif
							</div>
							@endif
							@if($bill->status!=1)
							<small>Reason: <span
									class="re{{encryptor('encrypt',$bill->id) }}">{{ $bill->cancel_reason }}
								</span></small>
							@endif
						</td>
						<td> <a href="{{route(currentUser().'.billShow',[encryptor('encrypt', $bill->id)])}}"
								class="btn btn-success">show</a> </td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			<!--end /table-->
		</div>
		<!--end /tableresponsive-->
		<div class="d-flex align-items-center justify-content-between">
			{{$allBill->links()}}
		</div>
	</div>
	<!--end card-body-->
</div>
<!--end card-->

@endsection

@push('scripts')
<script>
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
	});
	$(document).ready(function(){
		$('#payBillBtn').on('click', function(){
			$("#duePayForm").removeClass('d-none');
		})
		$(document).on('click', 'button#payDueForm', function(){
			$("#duePayForm").removeClass('d-none');
			let totalDue = $(this).attr('data-totalDue');
			$('input[name=payAmount]').val(totalDue);
		})

		$('#cancelPayDue').on('click', function(){
			$("#duePayForm").addClass('d-none')
			$('input[name=payAmount]').val(0);
		})
		$('#payDue').on('click', function(){
			let customerId = $('#payBillBtn').attr('data-customerId')
			let payAbleAmount = $('input[name=payAmount]').val();
			let totalDue = $('#payBillBtn').attr('data-totalDue')
			if(parseFloat(payAbleAmount) == 0.00) return alert('Please input amount');
			if(customerId != '' && parseFloat(payAbleAmount) != 0.00){
				$.ajax({
					url: "{{route('owner.payDue')}}",
					type:"POST",
					data: {
						customerId: customerId,
						payAbleAmount: payAbleAmount
					},
					success: function(response){ // What to do if we succeed
						if(response.status) {
							$("#duePayForm").addClass('d-none')
							$('input[name=payAmount]').val("1");
							location.reload();
						}else{
							console.log(response)
						}
					},
					error: function(response){
						console.log('Error'+response);
					}
				});
			}
		})
	});

function changeStatus(bid,status,ans) {
	
	if(status!=1)
		var reason=window.prompt("Reason of activity","");
	else
		var reason="";
	
	$.ajax({
		'url': "@if(currentUser() === 'owner' || currentUser() === 'salesmanager'){{route(currentUser().'.changeStatus')}}@endif",
		'type': 'GET',
		'data': {bid:bid,status:status,reason:reason},
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