@extends('layouts.width8')

@section('script')
    <script src="{{ asset('js/bundles/reports/create.js') }}" defer></script>
@endsection

@section('content')
    <create-report-component></create-report-component>
@endsection
