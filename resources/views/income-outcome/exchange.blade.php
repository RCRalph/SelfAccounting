@extends('layouts.width8')

@section('content')
    <exchange-means-of-payment-component type="{{ $viewType }}"></exchange-means-of-payment-component>
@endsection

@section('script')
    <script src="{{ asset('js/exchange-means-of-payment.js') }}" defer></script>
@endsection
