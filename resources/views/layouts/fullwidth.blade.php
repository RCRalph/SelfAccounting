@extends('layouts.app')

@section('wrapper')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </div>
@endsection

@section('script')
    @yield('script')
@endsection
