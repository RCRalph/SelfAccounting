@extends('layouts.width10')

@section('script')
    <script src="{{ asset('js/bundles/charts/io-by-type.js') }}" defer></script>
@endsection

@section('content')
    <io-by-type-component title="{{ $title }}"></io-by-type-component>
@endsection
