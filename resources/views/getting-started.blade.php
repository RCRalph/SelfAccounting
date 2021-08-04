@extends('layouts.width10')

@section('content')
    <div class="tutorial-wrapper" style="width: 100%;">
        <div class="tutorial-content" style="overflow-y: auto; max-height: none;">
            {!! Markdown::parse($text) !!}
        </div>
    </div>
@endsection
