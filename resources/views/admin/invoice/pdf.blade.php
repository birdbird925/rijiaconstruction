@extends('layouts.pdf')

@section('style')
    .invoice-table{
        width: 100%
    }

    .item-row td {
        vertical-align: top;
    }

    #top-section td {
        vertical-align: top;
    }

    .invoice-table .label {
        width: 35px;
        text-align: left;
    }

    .invoice-table .label-separator {
        width: 15px;
        text-align: center;
    }

    .invoice-table .main {
        width: calc(100% - 200px);
    }

    .invoice-table .end {
        width: 120px;
        text-align: right;
    }

    #top-section .label {
        width: 25px;
    }

    #top-section .label-separator {
        width: 10px;
    }

    #top-section .main {
        width: calc(100% - 185px);
        line-height: 20px;
    }

    #top-section .end {
        width: 150px;
        text-align: left;
    }

    #top-section .customer{
        padding-top: 4px
        font-size: 16px;
        font-weight: bold;
    }

    #top-section .invoice,
    #top-section .date,
    #top-section .po {
        float: right;
    }

    #top-section .invoice-row {
        margin-bottom: 12px;
    }

    #top-section .date-row {
        margin-bottom: 12px;
    }

    .invoice-body {
        margin-top: 30px;
    }

    #invoice-header td {
        font-weight: bold;
        padding: 0px 0px 5px;
        text-transform: uppercase;
    }

    #invoice-header .label {
        width: 40px;
    }

    .invoice-table .item-row td{
        padding-bottom: 10px;
    }

    .invoice-table .item-row .label span{
        display: inline-block;
        margin-left: 5px;
    }

    .total-row td {
        font-size: 14px;
        vertical-align: top;
    }

    .total-row .main {
        width: calc(100% - 180px);
        {{-- padding-left: 38px; --}}
        padding-left: 10px;
    }

    .total-row .end {
        width: 180px;
    }

    .total-row .total-label {
        vertical-align: text-bottom;
        display: inline-block;
        text-transform: uppercase;
        margin-right: 5px;
    }

    .total-row .total {
        font-weight: bold;
    }

    .total-row .price-in-text {
        margin-top: 40px;
        margin-bottom: 10px;
    }

    .sign {
        font-size: 14px;
        height: 70px;
        width: 180px;
        margin-top: 30px;
        {{-- margin-left: 48px; --}}
        margin-left: 20px;
        font-weight: bold;
        border-bottom: 2px dashed #000;
        text-transform: uppercase;
    }
    .material {
        line-height: 20px;
    }
@endsection
{{-- {{$date ? $date : date('d F, Y')}} --}}
@section('content')
    <table class="invoice-table" id="top-section">
        <tr>
            <td class="label">To</td>
            <td class="label-separator">:</td>
            <td class="main">
                <div class="customer">
                    {{str_replace("{~and~}", "&", $customer)}}
                </div>
                {!! $company ? str_replace("{~and~}", "&", $company).'<br>' : ''!!}
                {!! $company1 ? str_replace("{~and~}", "&", $company1).'<br>' : ''!!}
                {!! $company2 ? str_replace("{~and~}", "&", $company2) : ''!!}
            </td>
            <td class=end>
                <div class="invoice-row">
                    <label>Invoice</label>
                    <div class="invoice">{{$id}}</div>
                </div>
                <div class="date-row">
                    <label>Date</label>
                    <div class="date">{{$date ? $date : date('d/m/Y')}}</div>
                </div>
                @if($po != '')
                    <div class="po-row">
                        <label>PO No.</label>
                        <div class="po">{{str_replace("{~and~}", "&", $po)}}</div>
                    </div>
                @endif
            </td>
        </tr>
    </table>
    <div class="invoice-body">
        <table class="invoice-table" id="invoice-header">
            <tr>
                <td class="label">No. </td>
                <td class="label-separator"></td>
                <td class="main">Description</td>
                <td class="end">Amount</td>
            </tr>
        </table>
        <table class="invoice-table">
            @foreach($services as $index => $service)
                <tr class="item-row">
                    <td class="label">
                        <span>
                            {{$loop->iteration}}
                        </span>
                    </td>
                    <td class="label-separator"></td>
                    <td class="main">
                        {!!str_replace("{~and~}", "&", $service['text'])!!}
                    </td>
                    <td class="end">{{$service['price'] > 0 ? number_format($service['price'],2) : ''}}</td>
                </tr>
                @if($invoice_id >= 169)
                    @if($service['linebreak'] == 1) {
                        </table>
                        <div class="page-break"></div>
                        <table class="invoice-table">
                    }
                @elseif($invoice_id == '148')
                    @if($index == 12) 
                        </table>
                        <div class="page-break"></div>
                        <table class="invoice-table">
                    @endif
                @else 
                    @if(($index+1) % 9 == 0)
                        </table>
                        <div class="page-break"></div>
                        <table class="invoice-table">
                    @endif
                @endif
            @endforeach
            @if(sizeof($materials) != 0)
                <tr class="item-row">
                    <td class="label">
                        <span>
                            {{sizeof($services)+1}}
                        </span>
                    </td>
                    <td class="label-separator"></td>
                    <td class="main material">
                        @foreach($materials as $material)
                            {{str_replace("{~and~}", "&", $material['text'])}} {{$material['quantity']}} {{$material['unit']}} = RM{{($material['price']*$material['quantity'])}}
                            <br>
                        @endforeach
                    </td>
                    <td class="end">
                        {{$materialTotal}}
                    </td>
                </tr>
            @endif
        </table>
    </div>
@endsection

@section('footer')
    <table class="invoice-table">
        <tr class="total-row">
            <td class="label"></td>
            <td class="label-separator"></td>
            <td class="main">
                <div class="price-in-text">
                    {{$priceInText}}
                </div>
                <div class="payment-info">
                    Ri Jia Construction A/C No. (Maybank) 012026317103
                </div>
            </td>
            <td class="end">
                <span class="total-label">Total</span>
                <span class="total">{{$total ? number_format($total,2) : '0.00'}}</span>
                <br style="clear: both">
                <span class="total-label" style="vertical-align: middle">Deposit</span>
                <span class="total" style="vertical-align: middle">- {{ $deposit ? number_format($deposit, 2) : '0.00'}}</span>
                @if($discount)
                <br style="clear: both">
                <span class="total-label" style="vertical-align: middle">Discount</span>
                <span class="total" style="vertical-align: middle">- {{ $discount ? number_format($discount, 2) : '0.00'}}</span>
                @endif
                <hr>
                <span class="total-label" style="vertical-align: middle">Balance</span>
                <span class="total" style="vertical-align: middle">RM {{number_format($total - $deposit - ($discount ? $discount : 0), 2)}}</span>
            </td>
        </tr>
    </table>

    @if($note)
        <div class="note" style="padding-left: 20px; margin-top: 20px;">
            {!!$note!!}
        </div>
    @endif

    <div class="sign">
        <div class="company">
             Ri Jia Construction
        </div>
    </div>

    @if(isset($print))
        <script type="text/javascript">
        this.print(true);
        </script>
    @endif
@endsection
