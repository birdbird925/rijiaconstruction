@extends('layouts.admin')

@section('page-direction')
    Demo
@endsection

@push('demo-menu')
    menu-top-active
@endpush

@section('content')
    <div class="col-xs-12">
        <video controls buffered style="max-width: 100%; margin: auto;">
            <source src="/videos/demo.mp4" type="video/mp4">
            <source src="/videos/demo.webm" type="video/webm">
            <source src="/videos/demo.ogg" type="video/ogg">
        </video>
    </div>
@endsection
