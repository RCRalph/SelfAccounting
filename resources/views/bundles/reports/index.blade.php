@extends('layouts.width10')

@section('script')
    <script src="{{ asset('js/bundles/reports/all.js') }}" defer></script>
@endsection

@section('content')
    <reports-component></reports-component>
@endsection
