@extends('layouts.midwidth')

@section('script')
    <script src="{{ asset('js/income-outcome-create-one.js') }}" defer></script>
@endsection

@section('content')
    <income-outcome-create-one-component type="{{ $viewType }}"></income-outcome-create-one-component>
@endsection
