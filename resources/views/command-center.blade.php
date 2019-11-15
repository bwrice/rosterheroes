@extends('layouts.app')

@section('styles')
    <link href="{{ mix('/css/commandCenter.css') }}" rel="stylesheet">
@endsection

@section('content')
    <body>
    <div id="app">
        <router-view></router-view>
    </div>
    </body>
    <script src="{{ mix('/js/commandCenter.js') }}"></script>
@endsection
