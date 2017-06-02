@extends('layouts.auth')

@section('title')
    Register
@endsection

@section('content')
    <form action="{{route('register')}}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" name="name" value="{{old('name')}}" required autofocus placeholder="Name">
        </div>
        <div class="form-group">
            <input type="email" name="email" value="{{ old('email')}}" required placeholder="Email">
        </div>
        <div class="form-group">
            <input type="password" name="password" required placeholder="Password">
        </div>
        <div class="form-group">
            <input type="password" name="password_confirmation" required placeholder="Password Confirmation">
        </div>
        @include('auth.error')
        <button>Register</button>
    </form>
@endsection
