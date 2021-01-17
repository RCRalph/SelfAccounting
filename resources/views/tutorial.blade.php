@extends('layouts.width10')

@section('content')
    <div class="tutorial-wrapper-{{ $pageData["darkmode"] ? "dark" : "light" }}">
        {!! Markdown::parse($text) !!}
    </div>
@endsection
