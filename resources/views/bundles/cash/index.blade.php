@extends('layouts.width8')

@section('script')
    <script src="{{ asset('js/bundles/cash/cash.js') }}" defer></script>
@endsection

@section('content')
    <cash-component></cash-component>
@endsection
