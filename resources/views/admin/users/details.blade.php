@extends('layouts.width8')

@section('script')
    <script src="{{ asset('js/admin/users/details.js') }}" defer></script>
@endsection

@section('content')
    <user-details-component
        id="{{ $user->id }}"
    ></user-details-component>
@endsection
