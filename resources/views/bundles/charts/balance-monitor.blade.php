@extends('layouts.widthfull')

@section('script')
    <script src="{{ asset('js/bundles/charts/balance-monitor.js') }}" defer></script>
@endsection

@section('content')
    <balance-monitor-component></balance-monitor-component>
@endsection
