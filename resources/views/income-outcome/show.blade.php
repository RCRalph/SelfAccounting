@extends('layouts.midwidth')

@section('content')
    <income-outcome-edit-component type="{{ $viewType }}" ioid="{{ $id }}"></income-outcome-edit-component>
@endsection

@section('script')
    <script src="{{ asset('js/income-outcome-edit.js') }}" defer></script>
@endsection
