<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @page {
            margin: 5mm;
            margin-header: 0mm;
            margin-footer: 0mm;
        }

        * {
            padding: 0;
            margin: 0;
            font-family: sans-serif;
        }

        .wrapper {
            width: 796px;
            display: block;
            margin: auto;
            box-sizing: border-box;
            padding: 10px;
        }

        .header {
            justify-content: space-between;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 2px;
            margin-bottom: 2px;
        }

        .right p {
            text-align: right !important;
        }

        p {
            font-size: 13px;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .right p.border {
            font-size: 15px;
            font-weight: 400;
            padding: 10px;
            border: 1.5px solid #000;
            text-align: left;
            margin-bottom: 10px;
        }

        section.order-id,
        .order-date {
            display: flex;
            justify-content: space-between;
        }

        .large-space {
            margin-bottom: 20px;
        }

        .order-id-child-2 p {
            font-size: 17px;
        }

        .order-id-child-2 p span {
            margin-left: 10px;
        }

        .btn-of-head h5 {
            display: inline-block;
        }

        .btn-of-head h5:nth-child(odd) {
            margin-right: 40px;
        }

        thead tr th,
        tbody tr td {
            font-size: 11px;
        }

        .table td,
        .table th {
            border-top: none;
        }

        .table-sec thead {
            border-bottom: 2.4px solid #000 !important;
            border-top: 2px solid #000;
        }

        thead {
            background: transparent !important;
        }

        .total-count {
            display: flex;
        }

        .total-count table.table {
            width: 48%;
            display: inline-block;
            margin-right: 1%;
            overflow: hidden;
        }

        .total-count .note-sec {
            width: 48%;
            display: inline-block;
            margin-left: 1%;
            overflow: hidden;
        }

        .btm-of-total {
            padding: 20px;
            box-sizing: border-box;
            border: 1.5px solid #000;
        }

        .border-2 {
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            padding: 5px 0;
            display: flex;
            justify-content: space-between;
        }

        .mrp-total p span {
            text-align: right;
        }

        .all-count p {
            display: inline-block;
            margin-right: 40px;
        }

        .all-count p:last-of-type {
            float: right;
            margin-right: 0;
        }

        .up-section-left {
            width: 70%;
            margin-right: 2%;
            display: inline-block;
        }

        .thank {
            width: 25%;
            margin-left: 1%;
            display: inline-block;
        }

        footer {
            clear: both;
        }

        .up-footer {
            display: flex;
        }

        .thank h2 {
            margin-top: 25%;
        }

        .dwn-footer p {
            font-size: 13px;
        }

        .dwn-footer p:last-of-type {
            text-align: center;
            margin-top: 15px;
        }

        .dwn-footer p a {
            color: #000;
            text-decoration: none;
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

        thead {
            background: #abe18b;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
            text-transform: uppercase;
        }

        .table tbody+tbody {
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
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <table class="header">
            <tr>
                <td style="width:40%">
                    <h2>Invoice</h2>
                </td>
                <td style="text-align: right">
                    <h4>Invoice Number:
                        {{ $company->shopCode }}-{{date('y')}}-{{ str_pad($bill->bill_no,8,'0',STR_PAD_LEFT) }}</h4>
                    <p>Original for Recipient</p>
                    <p>Keep the original invoice for returns</p>
                </td>
            </tr>
        </table>
        <table style="width:100%">
            <tr>
                <td>
                    <h4>Sold By</h4>
                    <p class="large-space">{{ $company->company_name }}</p>
                    <p>{{ $company->company_add_a }}</p>
                    <p>{{ $company->company_add_b }}</p>
                </td>
                <td>
                    <p>Order Id <span>{{ str_pad($bill->bill_no,8,'0',STR_PAD_LEFT) }}</span></p>
                    <p>Order Date<span>{{ date('d/m/Y',strtotime($bill->bill_date)) }}</span></p>
                    <p>Invoice Date<span>{{ date('d/m/Y',strtotime($bill->bill_date)) }}</span></p>
                </td>
                <td>
                    <p>Bill To / Ship To (Patient)</p>
                    <p>{{ $bill->customer->name }},</p>
                    <p>{{ $bill->customer->address }}<br>
                        @if($bill->customer->custCode)
                        {{ $bill->customer->custCode }}
                        @endif
                        @if($bill->customer->contact_no_b)
                        , {{ $bill->customer->contact_no_b }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th>S NO</th>
                    <th>Description of Goods</th>
                    <th>Batch No</th>
                    <th>QTY</th>
                    <th>MRP</th>
                    <th>Discount</th>
                    <th>Tax</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($items)
                @foreach($items as $i=>$item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->product->brandName }}
					        @if($item->product->warrenty)
					           (warrenty: {{$item->product->warrenty}} )
					        @endif
					</td>
                    <td>{{ $item->batchId }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $company->currency }} {{ $item->price }}</td>
                    <td>{{ $company->currency }} {{ ($item->price*($item->discount/100)) }}</td>
                    <td>{{ $company->currency }} {{ ($item->price*($item->tax/100)) }}</td>
                    <td>{{ $company->currency }} {{ $item->amount }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        <hr>
        <table style="width:100%">
            <tr>
                <td style="border:1px solid black">
                    <p>Name of the <br>Salesperson: <span>@if($bill->user) {{ $bill->user->name }} @endif</span></p>
                    <p> PR Number<span>A-@if($bill->user) {{ str_pad($bill->user->id,3,'0',STR_PAD_LEFT) }}
                            @endif</span></p>
                    <p></p>
                </td>
                <td>
                    <h5 style="visibility: hidden">{{ $company->company_name }}</h5>
                </td>
                <td>
                    <p>MRP Total</p>
                    <p>Discount</p>
                    <p>Tax</p>
                </td>
                <td style="text-align: right">
                    <p>{{ $company->currency }} {{ $bill->sub_total}}</p>
                    <p>{{ $company->currency }} {{ $bill->total_dis}}</p>
                    <p>{{ $company->currency }} {{ $bill->total_tax}}</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="border-top:1px solid black">Total Invoice Amount .</td>
                <td style="border-top:1px solid black">{{ $company->currency }} {{ $bill->total_amount  }}</td>
            </tr>
        </table>
        <table style="width:100%">
            <tr>
                <td>
                    <pre style="font-size:10px">{{ $company->billing_terms }}</pre>
                </td>
                <td>
                    <h2>Thank You!</h2>
                    <p style="font-size:15px;">For ordering product with us</p>
                </td>
            </tr>
        </table>
        <div class="dwn-footer" style="text-align:center">
            <p>Contact For Support : {{$company->contact_number }} || Email: {{$company->company_email }}</p>
        </div>

        <div class="dwn-footer" style="text-align:right; position:fixed; bottom:10px; right:5px">
            <p>powered by E-Cash</p>
        </div>
    </div>
</body>

</html>