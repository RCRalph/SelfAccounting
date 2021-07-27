@extends('layouts.width8')

@section('script')
    <script src="{{ mix('js/bundles/reports/create.js') }}" defer></script>
@endsection

@section('content')
    <create-report-component></create-report-component>
@endsection
