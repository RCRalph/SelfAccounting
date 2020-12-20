@extends('layouts.fullmidwidth')

@section('script')
    <script src="{{ asset('js/admin/users/list.js') }}" defer></script>
@endsection

@section('content')
    <user-list-component></user-list-component>
@endsection
