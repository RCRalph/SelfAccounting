@extends('layouts.width8')

@section('script')
    <script src="{{ mix('js/payment.js') }}" defer></script>
@endsection

@section('content')
    <payment-component
        id="{{ $id }}"
        user="{{ $userId }}"
    ></payment-component>
@endsection
