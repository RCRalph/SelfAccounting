@extends('layouts.width6')

@section('script')
    <script src="{{ mix('js/admin/users/details.js') }}" defer></script>
@endsection

@section('content')
    <user-details-component
        id="{{ $user->id }}"
    ></user-details-component>
@endsection
