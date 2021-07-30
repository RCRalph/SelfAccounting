@extends('layouts.width6')

@section('script')
    <script src="{{ mix('js/admin/bundles/list.js') }}" defer></script>
@endsection

@section('content')
    <bundle-list-component></bundle-list-component>
@endsection
