@extends('layouts.width8')

@section('script')
    <script src="{{ mix('js/admin/bundles/create.js') }}" defer></script>
@endsection

@section('content')
    <create-bundle-component></create-bundle-component>
@endsection
