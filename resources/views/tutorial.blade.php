@extends('layouts.width10')

@section('content')
    <div class="tutorial-wrapper">
        {!! Markdown::parse($text) !!}
    </div>
@endsection
