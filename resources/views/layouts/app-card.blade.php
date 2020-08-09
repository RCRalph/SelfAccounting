@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    @yield('card-content')
                </div>
            </div>
        </div>
    </div>
@endsection
