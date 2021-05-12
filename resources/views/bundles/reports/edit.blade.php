@extends('layouts.width8')

@section('script')
    <script src="{{ asset('js/bundles/reports/edit.js') }}" defer></script>
@endsection

@section('content')
    <edit-report-component id="{{ $id }}"></edit-report-component>
@endsection
