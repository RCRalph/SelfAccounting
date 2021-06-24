@extends('layouts.width8')

@section('script')
    <script src="{{ mix('js/income-outcome-create-multiple.js') }}" defer></script>
@endsection

@section('content')
    <income-outcome-create-multiple-component type="{{ $viewType }}"></income-outcome-create-multiple-component>
@endsection
