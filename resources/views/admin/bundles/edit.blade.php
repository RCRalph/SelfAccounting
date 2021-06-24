@extends('layouts.width8')

@section('script')
    <script src="{{ mix('js/admin/bundles/edit.js') }}" defer></script>
@endsection

@section('content')
    <edit-bundle-component
        id="{{ $bundle->id }}"
    ></edit-bundle-component>
@endsection
