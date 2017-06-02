@extends('layouts.auth')

@section('title')
    Forget Password
@endsection

@section('content')
    <form method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        @include('auth.error')
        <button>Send Password Reset Link</button>
    </form>
@endsection
