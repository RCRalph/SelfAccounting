@extends('layouts.midwidth')

@section('script')
    <script src="{{ asset('js/income-outcome-create-multiple.js') }}" defer></script>
@endsection

@section('content')
    <income-outcome-create-multiple-component type="{{ $viewType }}"></income-outcome-create-multiple-component>
@endsection
