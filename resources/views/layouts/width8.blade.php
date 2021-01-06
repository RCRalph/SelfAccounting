@extends('layouts.app')

@section('wrapper')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
