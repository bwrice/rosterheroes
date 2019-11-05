@extends('layouts.app')

@section('content')
    <body>
    <div id="app">
        <router-view></router-view>
    </div>
    </body>
    <script src="{{ mix('/js/commandCenter.js') }}"></script>
@endsection
