@extends('layouts.home')

@section('content')
    <reset-component token="{{ $token }}" email="{{ $email }}"></reset-component>
@endsection
