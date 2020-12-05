@extends('layouts.fullwidth')

@section('script')
    <script src="{{ asset('js/income-outcome.js') }}" defer></script>
    <script src="{{ asset('js/table-hovering.js') }}" defer></script>
@endsection

@section('content')
    <income-outcome-component type="{{ $viewType }}"></income-outcome-component>
@endsection
