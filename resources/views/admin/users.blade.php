@extends('layouts.fullmidwidth')

@section('script')
    <script src="{{ asset('js/users.js') }}" defer></script>
@endsection

@section('content')
    <users-component></users-component>
@endsection
