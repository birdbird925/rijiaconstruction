@extends('layouts.admin')

@section('page-direction')
    Quotations
@endsection

@section('page-button')
    <a href="/admin/quotation/create" class="btn btn-primary">New</a>
@endsection

@push('quotation-menu')
    menu-top-active
@endpush

@section('content')
    <div class="col-sm-12">
        @if($quotations->count() == 0)
            There are not any quotations yet.
            <br>
            <a href="/admin/quotation/create">Create quotation</a>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="quotation-table">
                    <thead>
                        <tr>
                            <th>Ref. No.</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $quotation)
                            <tr>
                                <td>{{$quotation->refNumber()}}</td>
                                <td>{{substr($quotation->date, 0, 10)}}</td>
                                <td>{{$quotation->to}}</td>
                                <td>{{$quotation->title}}</td>
                                <td>{{$quotation->total()}}</td>
                                <td>
                                    @if($quotation->status)
                                        <label class="label label-success">Approved</label>
                                    @else
                                        <a href="/admin/invoice/create?quotation={{$quotation->id}}"><label class="label label-warning">Pending</label></a>
                                    @endif
                                </td>
                                <td class="table-control">
                                    <a href="/admin/quotation/{{$quotation->id}}/edit"><i class="fa fa-pencil"></i></a>
                                    <a href="/admin/quotation/{{$quotation->id}}/pdf"><i class="fa fa-eye"></i></a>
                                    <a href="/admin/quotation/{{$quotation->id}}/print" target="_blank"><i class="fa fa-print"></i></a>
                                    <button class="btn-delete" data-target="#form-{{$quotation->id}}"><i class="fa fa-trash"></i></button>
                                    <form action="/admin/quotation/{{$quotation->id}}" method="post" id="form-{{$quotation->id}}">
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
