@extends('layouts.midwidth')

@section('script')
    <script src="{{ asset('js/admin/user-details.js') }}" defer></script>
@endsection

@section('content')
    <user-details-component
        start="{{ $start ?? "" }}"
    ></user-details-component>
@endsection
