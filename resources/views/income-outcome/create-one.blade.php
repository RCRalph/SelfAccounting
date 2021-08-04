@extends('layouts.width6')

@section('script')
    <script src="{{ mix('js/income-outcome-create-one.js') }}" defer></script>
@endsection

@section('content')
    <income-outcome-create-one-component type="{{ $viewType }}"></income-outcome-create-one-component>
@endsection
