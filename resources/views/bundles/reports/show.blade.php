@extends('layouts.width12')

@section('script')
    @include("scripts.tableHovering")
    <script src="{{ asset('js/bundles/reports/show.js') }}" defer></script>
@endsection

@section('content')
    <show-report-component id="{{ $report->id }}" edit="{{ auth()->user()->id == $report->user_id ? "1" : "0" }}"></show-report-component>
@endsection
