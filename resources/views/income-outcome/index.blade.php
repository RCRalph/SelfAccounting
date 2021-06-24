@extends('layouts.width12')

@section('script')
    @include("scripts.tableHovering")
    <script src="{{ mix('js/income-outcome.js') }}" defer></script>
@endsection

@section('content')
    <income-outcome-component type="{{ $viewType }}"></income-outcome-component>
@endsection
