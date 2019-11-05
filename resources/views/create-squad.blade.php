@extends('layouts.app')

@section('content')
    <body style="overflow-y: hidden">
    <div id="app">
        @if($squad)
            <create-squad :squad="{{$squad}}" :heroes="{{$heroes}}" :allowed-hero-classes-prop="{{$heroClasses}}" :allowed-hero-races-prop="{{$heroRaces}}"></create-squad>
        @else
            <create-squad></create-squad>
        @endif
    </div>
    </body>
    <script src="{{ mix('/js/createSquad.js') }}"></script>
@endsection
