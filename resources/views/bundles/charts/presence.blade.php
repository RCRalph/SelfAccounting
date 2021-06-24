@extends('layouts.width8')

@section('script')
    <script src="{{ mix('js/bundles/charts/presence.js') }}" defer></script>
@endsection

@section('content')
    <presence-component></presence-component>
@endsection
