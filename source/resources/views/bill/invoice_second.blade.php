<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
	@import url('https://fonts.maateen.me/kalpurush/font.css');
    *{
    padding: 0;
    margin: 0;
    font-family: sans-serif;
}

.wrapper{
    width: 98%;
    display: block;
    margin: auto;
    box-sizing: border-box;
    padding: 10px;
}

.text-center{
    text-align: center;
}

.wrapper .header h2{
    font-size: 30px;
    padding: 8px;
    background: #afcd9d;
    
}
span{
    display: block;
    line-height: 1.3em;
    font-weight: 500;
}

.contact,.info{
    padding-top: 25px;
    display: flex;
}
.contact .width-5,.info .width-5{
    width: 45%;
    display: inline-block;
    box-sizing: border-box;
    
}
.contact .width-5.mr,.info .width-5.mr{
    margin-right: 2%;
}
.contact .width-5 h2{
    padding: 50px;
    border: 1px solid #ddd;
    font-size: 20px;
    font-weight: 500;
}
.pn{
    padding: 30px;
}
.padding-w{
    box-sizing: border-box;
    padding: 0px;
}
.padding-w h4{
    padding: 10px;
    background: red;
    background: #abe18b;
}
.padding-w p{
    padding: 10px;
    background: #ddd;
}
.total-cost,.tax{
    position: relative;
}

.total-cost .ttl-p{
    position: absolute;
    right: 93px;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    border-collapse: collapse;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, .05)
}
.table td,
.table th {
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6
}
thead{
    background: #abe18b;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    text-align: left;
    text-transform: uppercase;
}

.table tbody + tbody {
    border-top: 2px solid #dee2e6
}

.table .table {
    background-color: #fff
}


.table-bordered {
    border: 1px solid #dee2e6
}

.table-bordered td,
.table-bordered th {
    border: 1px solid #dee2e6
}

.table-bordered thead td,
.table-bordered thead th {
    border-bottom-width: 2px
}
.tax{
    position: relative;
    top: 30px;
}
.tax span{
    position: absolute;
    right: 93px;
}
.unit{
    position: relative;
    top: 50px;
}
.unit .qt,.unit .amnt{
    position: absolute;
    display: inline-block;
}
.unit .qt{
    right: 360px;
}
.unit .amnt{
    right: 93px;
}

</style>

</head>

<body>
    <div class="wrapper">
        <div class="header text-center">
            <h2 style="text-transform: uppercase" style="font-family: bangla; margin: 0; margin-top:-20px">INVOICE</h2>
        </div>
        <div class="contact">
            <div class="width-5 mr" style="width: 50%; float:left">
                <div style="width: 80%">
                    <img src="{{asset("storage/images/company/$company->company_logo")}}" alt="" width="100%" />
                </div>
            </div>
            <div class="width-5 padding-w" style="width: 50%; float:right">
                <div style="width: 80%; float:right">
                    <h4 class="text-center" style="margin: 0;font-size:18px">Invoice# {{ $company->shopCode }}-{{date('y')}}-{{ str_pad($bill->bill_no,8,'0',STR_PAD_LEFT) }}</h4>
                    <p class="text-center" style="margin: 0;font-size:18px">Date# {{ date('d/m/Y',strtotime($bill->bill_date)) }}</p>
                </div>
            </div>
        </div>
        <div class="info">
            <div class="width-5 padding-w mr" style="width: 50%; float:left">
                <div style="width: 80%; padding-left: 5px;">
                    <span><em>From:</em></span><br>
                    <span>{{ $company->company_name }}</span><br>
                    <span>{{ $company->company_add_a }}</span><br>
                    @if($company->company_add_b)
                        <span>{{ $company->company_add_b }}</span><br>
					@endif
                    @if($company->company_email)
						<span>{{ $company->company_email }}</span><br>
					@endif
                    <span>{{ $company->contact_number }}</span>
                </div>
            </div>
			<div class="width-5 padding-w" style="width: 50%; float:right">
                <div style="width: 80%; float: right; padding-left: 5px">
                    <span><em>To:</em></span>
                    <span>{{ $bill->customer->name }}</span><br>
                    <span>{{ $bill->customer->address }}</span><br>
                    <span>@if($bill->customer->email)
						{{ $bill->customer->email }}
					@endif</span><br>
                    <span>@if($bill->customer->custCode)
						{{ $bill->customer->custCode }}
					@endif
                </div>
            </div>
        </div>
        <div class="table-info" style="padding-top: 15px;padding-bottom: 15px;">
            <table class="table table-bordered table-striped" style="border:0px">
                <thead>
                    <tr>
                        <th>Items Name</th>
                        <th>Unit Cost</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
					@php $qty=0; @endphp
					@if($items)
						@foreach($items as $item)
							@php $qty+=round($item->qty); @endphp
						<tr>
							<td>{{ $item->product->brandName }}
						        @if($item->product->warrenty)
						           (warrenty: {{$item->product->warrenty}} )
						        @endif
						    </td>
							<td>{{ $company->currency }} {{ $item->price }}</td>
							<td>{{ round($item->qty) }}</td>
							<td>{{ $company->currency }} {{ ($item->price*($item->discount/100)) }}</td>
							<td>{{ $company->currency }} {{ ($item->price*($item->tax/100)) }}</td>
							<td>{{ $company->currency }} {{ $item->price*$item->qty }}</td>
						</tr>
						@endforeach
					@endif
                </tbody>
                <tfoot>
                    <tr>
                        <td style="border:0px;padding:3px 10px;font-weight:bold">Sub Total</td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold">{{ $qty }}</td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold">{{ $company->currency }} {{ $bill->sub_total }}</td>
                    </tr>
                    <tr>
                        <td style="border:0px;padding:3px 10px;font-weight:bold">Discount</td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold">{{ $company->currency }}{{ $bill->total_dis }}</td>
                    </tr>
                    <tr>
                        <td style="border:0px;padding:3px 10px;font-weight:bold">Tax</td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:3px 10px;font-weight:bold">{{ $company->currency }} {{ $bill->total_tax }}</td>
                    </tr>
                    <tr>
                        <td style="border:0px;padding:3px 10px;font-weight:bold">Total</th>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:0 10px;font-weight:bold"></td>
                        <td style="border:0px;padding:3px 10px;font-weight:bold">{{ $company->currency }} {{ $bill->total_amount }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="border: 1px solid #ddd;">
            <p style="padding: 5px;background: #afcd9d; width: 99%; margin-top:0">Payment Instraction</p>
            <pre style="padding-left:15px">{{ $company->billing_terms }}</pre>
        </div>
            <div class="sig" style="margin-top: 10px;">
                <p>Signeture</p>
                <p> <img alt="Pic" src="{{asset("storage/images/company/$company->billing_signature")}}" width="100px"> </p><p style="float:right;margin-top:-40px;text-align:right"><span>powered by E-Cash</span></p>
                
            </div>
    </div>
</body>

</html>