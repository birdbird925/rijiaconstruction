@extends('layouts.pdf')

@section('style')
    .quotation-table{
        width: 100%
    }

    .quotation-table tr .label {
        width: 40px;
        text-align: left;
    }

    .quotation-table tr .label-separator {
        width: 20px;
        text-align: center;
    }

    .quotation-table tr .main {
        width: calc(100% - 180px);
    }

    .quotation-table tr .end {
        width: 120px;
        text-align: right;
    }

    .quotation-table .date-row td {
        padding-bottom: 5px;
    }

    .quotation-table .customer-row td {
        padding-bottom: 15px;
        vertical-align: text-top;
    }

    .quotation-table .customer-row .main {
        width: calc(100% - 500px);
        line-height: 14px;
        padding-top: 3px;
    }

    .quotation-table .customer-row .end {
        width: 300px;
    }

    .quotation-table .title-row .title {
        display: inline-block;
        font-size: 16px;
        font-weight: bold;
        padding-bottom: 2px;
        border-bottom: 2px solid #000;
    }

    .quotation-table .amount-header td{
        padding-bottom: 5px;
    }

    .quotation-table .amount-header .end {
        text-align: right;
    }

    .quotation-table .amount-header .end .amount-header{
        display: inline-block;
        text-align: right;
        border-bottom: 1px solid #000;
    }

    .quotation-table .item-row td {
        vertical-align: text-top;
        padding-bottom: 8px;
    }

    .quotation-table .item-row .label {
        text-align: right;
    }

    .quotation-table .total-row td {
        vertical-align: text-top;
    }

    .quotation-table .total-row .main {
        text-align: right;
        text-transform: uppercase;
        /*padding-top: 8px;*/
    }

    .quotation-table .total-row .total{
        float: right;
        font-weight: bold;
        display: inline-block;
        /*padding: 5px 0 5px 10px;
        border-top: 2px solid #000;
        border-bottom: 2px double #000;*/
    }
@endsection

@section('content')
    <table class="quotation-table">
        <tr class="date-row">
            <td class="label">Date</td>
            <td class="label-separator">:</td>
            <td class="main">{{$date ? $date : date('d F, Y')}}</td>
            <td class=end></td>
        </tr>
        <tr class="customer-row">
            <td class="label">To</td>
            <td class="label-separator">:</td>
            <td class="main">
                {{$customer ? str_replace("{~and~}", "&", $customer) : 'Mr. Lee'}}
                {!! $tel ? '<br>'.str_replace("{~and~}", "&", $tel).' ' : '' !!}
                {!! $email ? '<br>'.str_replace("{~and~}", "&", $email) : '' !!}
            </td>
            <td class="end">
                @if($address1 || $address2)
                    {!! str_replace("{~and~}", "&", $address1) !!}
                    {!! '<br>'.str_replace("{~and~}", "&", $address2) !!}
                @endif
            </td>
        </tr>
        <tr class="title-row">
            <td class="label">Re</td>
            <td class="label-separator">:</td>
            <td class="main"><div class="title">{{$title ? str_replace("{~and~}", "&", $title) : 'House Renovation Quotation'}}</div></td>
            <td class="end"></td>
        </tr>
        <tr class="amount-header">
            <td class="label"></td>
            <td class="label-separator"></td>
            <td class="main"></td>
            <td class="end">Amount</td>
        </tr>
        @foreach($services as $service)
            <tr class="item-row">
                <td class="label">{{$loop->iteration}}</td>
                <td class="label-separator"></td>
                <td class="main">
                    {!!str_replace("{~and~}", "&", $service['text'])!!}
                </td>
                <td class="end">{{number_format($service['price'],2)}}</td>
            </tr>
        @endforeach
        <tr class="material-row">
            <td class="label"></td>
            <td class="label-separator"></td>
            <td class="main">
                @foreach($materials as $count=>$material)
                    {{str_replace("{~and~}", "&", $material['text'])}} {{$material['quantity']}} {{$material['unit']}} = RM{{number_format(($material['price']*$material['quantity']),2)}}
                    @if($loop->iteration < sizeof($materials))
                        <br>
                    @endif
                @endforeach
            </td>
            <td class="end">
                {{$materialTotal}}
            </td>
        </tr>
    </table>
@endsection

@section('footer')
    <table class="quotation-table">
        <tr class="total-row">
            <td class="label"></td>
            <td class="label-separator"></td>
            <td class="main">TOTAL</td>
            <td class="end"><span class="total">{{$total ? $total : '0.00'}}</span></td>
        </tr>
    </table>

    @if($note)
        {!! $note !!}
    @endif

    @if(isset($print))
        <script type="text/javascript">
        this.print(true);
        </script>
    @endif
@endsection
