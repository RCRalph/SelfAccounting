@extends('layouts.width6')

@section('script')
    <script src="{{ mix('js/summary.js') }}" defer></script>
@endsection

@section('content')
    <summary-component></summary-component>
@endsection
