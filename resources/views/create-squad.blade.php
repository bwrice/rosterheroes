@extends('layouts.app')

@section('content')
    <div id="app">
        @if($squad)
            <create-squad :squad="{{$squad}}" :heroes="{{$heroes}}" :allowed-hero-classes="{{$heroClasses}}" :allowed-hero-races="{{$heroRaces}}"></create-squad>
        @else
            <create-squad></create-squad>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('/js/createSquad.js') }}"></script>
@endsection
