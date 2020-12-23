@extends('layouts.midwidth')

@section('script')
    <script src="{{ asset('js/admin/bundles/edit.js') }}" defer></script>
@endsection

@section('content')
    <edit-bundle-component
        id="{{ $bundle->id }}"
    ></edit-bundle-component>
@endsection
