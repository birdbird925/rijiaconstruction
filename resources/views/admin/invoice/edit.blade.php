@extends('layouts.admin')

@section('page-direction')
    <a href="/admin/invoice">Invoice</a>
    /
    {{$invoice->refNumber()}}
    /
    Edit
@endsection

@section('page-button')
    <button type="button" class="btn btn-primary btn-save" data-form="#main-form">SAVE</button>
@endsection

@push('invoice-menu')
    menu-top-active
@endpush

@section('content')
    @include('admin.popup.service')
    @include('admin.popup.material')
    <div class="col-sm-5">
        <form action="/admin/invoice/{{$invoice->id}}" method="post" data-type="invoice" id="main-form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <input type="hidden" name="quotation" value="{{$invoice->quotation_id}}">
            <div class="form-group">
                <label>Date:</label>
                <input type="text" name="date" id="datepicker" class="form-control" value="{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('m/d/Y')}}" default="{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('m/d/Y')}}" required>
            </div>
            <div class="form-group">
                <label>To:</label>
                <input type="text" name="customer" class="form-control" value="{{$invoice->to}}" default="{{$invoice->to}}" required>
            </div>
            <div class="form-group">
                <label>Company:</label>
                <input type="text" name="company_line_1" class="form-control" value="{{$invoice->company_line_1}}">
                <br>
                <input type="text" name="company_line_2" class="form-control" value="{{$invoice->company_line_2}}">
            </div>
            <div class="form-group">
                <label>PO No:</label>
                <input type="text" name="po" class="form-control" value="{{$invoice->purchase_order}}">
            </div>
            <div class="form-group">
                <label>Deposit:</label>
                <input type="number" name="deposit" class="form-control" value={{$invoice->deposit}}>
            </div>
            <div class="form-group">
                <input type="checkbox" id="material-included" name="material-included" value="{{$invoice->material_included ? 'true' : 'false'}}" {{$invoice->material_included ? 'checked' : 'false'}}><label for="material-included" style="margin-left: 10px;"> Material cost was included</label>
            </div>
            <hr>
            <div class="sub-title">Services</div>
            <div class="service-list data-list">
                @if($invoice->services != null)
                    @foreach($invoice->services as $key=>$service)
                        <div class="service-item data-item" id="service-{{$key}}">
                            <p class="service main-data">
                                {!!$service->text!!}
                            </p>
                            <div>
                                <span class="price">
                                    RM{{$service->price}}
                                </span>
                                <a class="btn-edit" data-toggle="modal" data-target="#serviceModal">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn-delete">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <input type="hidden" class="inputText" name="service[{{$key}}][text]" value="{{$service->text}}" data-input="textarea[name=service]">
                            <input type="hidden" class="inputPrice" name="service[{{$key}}][price]" value="{{$service->price}}" data-input="input[name=price]">
                            <span class="update-target" data-target="#service-{{$key}}"></span>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#serviceModal"><i class="fa fa-plus-circle"></i> Add services</button>
            <hr>
            <div class="sub-title">Materials</div>
            <div class="material-list data-list">
                @if($invoice->materials != null)
                    @foreach($invoice->materials as $key=>$material)
                        <div class="material-item data-item" id="material-{{$key}}">
                            <p class="main-data">
                                <span class="material">{{$material->text}}</span>
                                <span class="quantity">{{$material->quantity}}</span>
                                <span class="unit">{{$material->unit}}</span>
                                =
                                <span class="price">RM{{$material->price}}</span>
                                <a class="btn-edit" data-toggle="modal" data-target="#materialModal">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn-delete">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </p>
                            <input type="hidden" class="inputQuantity" name="material[{{$key}}][quantity]" value="{{$material->quantity}}" data-input="input[name=quantity]">
                            <input type="hidden" class="inputUnit" name="material[{{$key}}][unit]" value="{{$material->unit}}" data-input="input[name=unit]">
                            <input type="hidden" class="inputText" name="material[{{$key}}][text]" value="{{$material->text}}" data-input="textarea[name=material]">
                            <input type="hidden" class="inputPrice" name="material[{{$key}}][price]" value="{{$material->price}}" data-input="input[name=price]">
                            <span class="update-target" data-target="#material-{{$key}}"></span>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#materialModal"><i class="fa fa-plus-circle"></i> Add material</button>
        </form>
    </div>
    <div class="col-sm-6 col-sm-offset-1">
        <div class="pdf-preview">
            <object class="preview-obj" data="/admin/invoice/{{$invoice->id}}/pdf#toolbar=0&navpanes=0" type="application/pdf" width="100%" height="760px">
            </object>
        </div>
    </div>
@endsection
