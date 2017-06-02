@extends('layouts.admin')

@section('page-direction')
    Account
@endsection

@push('account-menu')
    menu-top-active
@endpush


@section('content')
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-5">
                <form action="/admin/account/email" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Old email</label>
                        <input type="email" class="form-control" value="{{Auth::user()->email}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>New email</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Save">
                </form>
            </div>
            <div class="col-sm-5 col-sm-offset-1">
                <form action="/admin/account/password" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Old password</label>
                        <input type="password" name="Old_Password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>New password</label>
                        <input type="password" name="New_Password" class="form-control">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Save">
                </form>
            </div>
        </div>
    </div>
@endsection
