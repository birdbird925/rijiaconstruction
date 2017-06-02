@extends('layouts.auth')

@section('title')
    login
@endsection

@section('content')
    <form action="{{route('login')}}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="email" name="email" value="{{ old('email')}}" required autofocus placeholder="Email">
        </div>
        <div class="form-group">
            <input type="password" name="password" required placeholder="Password">
        </div>
        @include('auth.error')
        <button>Login</button>
    </form>
    <p class="footer">
        <a class="btn btn-link" href="{{ route('password.request') }}">
            Forgot Your Password?
        </a>
    </p>
@endsection
