@extends('layouts.auth')

@section('title')
    Reset Password
@endsection

@section('content')
    <form method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <input id="email" type="email" name="email" value="{{ $email or old('email') }}" placeholder="Email" required autofocus>
        <input id="password" type="password" name="password" placeholder="Password" required>
        <input id="password-confirm" type="password" placeholder="Confirm Password" name="password_confirmation" required>
        @include('auth.error')
        <button>Reset Password</button>
    </form>
@endsection
