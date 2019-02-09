@extends('layouts.app')

@section('content')
    <div id="app">
        <command-center></command-center>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('/js/command-center.js') }}"></script>
@endsection