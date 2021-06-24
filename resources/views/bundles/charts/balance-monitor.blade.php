@extends('layouts.widthfull')

@section('script')
    <script src="{{ mix('js/bundles/charts/balance-monitor.js') }}" defer></script>
@endsection

@section('content')
    <balance-monitor-component></balance-monitor-component>
@endsection
