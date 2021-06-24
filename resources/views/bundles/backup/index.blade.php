@extends('layouts.width12')

@section('script')
    <script src="{{ mix('js/bundles/backup/backup.js') }}" defer></script>
@endsection

@section('content')
    <backup-component></backup-component>
@endsection
