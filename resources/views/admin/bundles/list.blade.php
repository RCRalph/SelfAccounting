@extends('layouts.width8')

@section('script')
    <script src="{{ asset('js/admin/bundles/list.js') }}" defer></script>
@endsection

@section('content')
    <bundle-list-component></bundle-list-component>
@endsection
