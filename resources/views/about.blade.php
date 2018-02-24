@extends('layouts.app')

@section('heading')
    {{ config('app.name') }}
@endsection

@section('content')
    <p>This is a simple, open source file-sharing application written in PHP and built on the Laravel framework.</p>
    @include('layouts.links')
@endsection