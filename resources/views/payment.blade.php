@extends('layouts.width8')

@section('script')
    <script src="{{ asset('js/payment.js') }}" defer></script>
@endsection

@section('content')
    <payment-component
        id="{{ $id }}"
        user="{{ $userId }}"
    ></payment-component>
@endsection
