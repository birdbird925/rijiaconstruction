@extends('layouts.admin')

@section('page-direction')
    Invoices
@endsection

@push('invoice-menu')
    menu-top-active
@endpush

@section('content')
    <div class="col-sm-12">
        @if($invoices->count() == 0)
            There are not any invoices yet.
            <br>
            <a href="/admin/quotation">Browse quotation</a>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="invoice-table">
                    <thead>
                        <tr>
                            <th>Ref. No.</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Company</th>
                            <th>Amount</th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{$invoice->refNumber()}}</td>
                                <td>{{substr($invoice->date, 0, 10)}}</td>
                                <td>{{$invoice->to}}</td>
                                <td>{{$invoice->company_line_1}}</td>
                                <td>{{$invoice->total()}}</td>
                                <td class="table-control">
                                    <a href="/admin/invoice/{{$invoice->id}}/edit"><i class="fa fa-pencil"></i></a>
                                    <a href="/admin/invoice/{{$invoice->id}}/pdf"><i class="fa fa-eye"></i></a>
                                    <a href="/admin/invoice/{{$invoice->id}}/print" target="_blank"><i class="fa fa-print"></i></a>
                                    <button class="btn-delete" data-target="#form-{{$invoice->id}}"><i class="fa fa-trash"></i></button>
                                    <form action="/admin/invoice/{{$invoice->id}}" method="post" id="form-{{$invoice->id}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
