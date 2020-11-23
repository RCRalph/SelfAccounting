@extends('layouts.midwidth')

@section('script')
    <script src="{{ asset('js/summary.js') }}" defer></script>
@endsection

@section('content')
    <summary-component></summary-component>
@endsection
