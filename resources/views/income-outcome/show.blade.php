@extends('layouts.midwidth')

@section('script')
<script src="{{ asset('js/income-outcome-edit.js') }}" defer></script>
@endsection

@section('content')
<income-outcome-edit-component type="{{ $viewType }}" ioid="{{ $id }}"></income-outcome-edit-component>
@endsection
