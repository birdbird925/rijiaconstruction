@extends('layouts.admin')

@section('page-direction')
    <a href="/admin/quotation">Quotations</a>
    /
    New
@endsection

@section('page-button')
    <button type="button" class="btn btn-primary btn-save" data-form="#main-form">SAVE</button>
@endsection

@push('quotation-menu')
    menu-top-active
@endpush

@section('content')
    @include('admin.popup.service')
    @include('admin.popup.material')
    <div class="col-sm-5">
        <form action="/admin/quotation" data-type="quotation" method="post" id="main-form">
            {{ csrf_field() }}
            <div class="form-group">
                <label>Date:</label>
                <input type="text" name="date" id="datepicker" class="form-control" value="{{ date('m/d/Y')}}" default="{{ date('m/d/Y')}}" required>
            </div>
            <div class="form-group">
                <label>To:</label>
                <input type="text" name="customer" class="form-control" value="Mr. Lee" default="Mr. Lee" required>
            </div>
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" class="form-control" value="House Renovation Quotation" default="House Renovation Quotation" required>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address_line_1" class="form-control">
                <br>
                <input type="text" name="address_line_2" class="form-control">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Tel:</label>
                <input type="text" name="tel" class="form-control">
            </div>
            <div class="form-group">
                <label>Note:</label>
                <textarea name="note" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <input type="checkbox" id="material-included" name="material-included" value="true" checked><label for="material-included" style="margin-left: 10px;"> Material cost was included</label>
            </div>
            <hr>
            <div class="sub-title">Services</div>
            <div class="service-list data-list"></div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#serviceModal"><i class="fa fa-plus-circle"></i> Add services</button>
            <hr>
            <div class="sub-title">Materials</div>
            <div class="material-list data-list"></div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#materialModal"><i class="fa fa-plus-circle"></i> Add material</button>
        </form>
    </div>
    <div class="col-sm-6 col-sm-offset-1">
        <div class="pdf-preview">
            <object class="preview-obj" data="/admin/quotation/preview?date={{ date('m/d/Y')}}&customer=Mr. Lee&title=House Renovation Quotation#toolbar=0&navpanes=0" type="application/pdf" width="100%" height="760px">
            </object>
        </div>
    </div>
@endsection
