@extends('layouts.width10')

@section('script')
    <script src="{{ mix('js/profile.js') }}" defer></script>
@endsection

@section('content')
    <profile-component></profile-component>
@endsection
